<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Mail\VerificationMail;
use App\Models\Credit;
use App\Models\User;
use App\Services\Settings\CachedSettingsRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->leftJoin(DB::raw("
                (select count(shopper_id) as shopper_count,shopper_id from orders
                group by orders.shopper_id) as shopper
            "), 'shopper.shopper_id', '=', 'users.id')
            ->leftJoin(DB::raw("
                (select count(earner_id) as earner_count,earner_id from orders
                group by orders.earner_id) as earner
            "), 'earner.earner_id', '=', 'users.id')
            ->leftJoin(DB::raw("
            (select user_id,sum(amount) btc_amount from credits
                where credits.currency = 'btc'
                group by credits.user_id) as btc_credit
            "), 'btc_credit.user_id', '=', 'users.id')
            ->leftJoin(DB::raw("
                (select user_id,sum(amount) usdt_amount from credits
                where credits.currency = 'usdt'
                group by credits.user_id) as usdt_credit
            "), 'usdt_credit.user_id', '=', 'users.id');

        if ($request->query('user')) {
            if (filter_var($request->user, FILTER_VALIDATE_EMAIL)) {
                $users = $users->where('email', 'like', '%' . $request->query('user') . '%');
            } else {
                $users = $users->where('username', 'like', '%' . $request->query('user') . '%');
            }
        }

        if ($request->query('vip')) {
            $users = $users->where('fast_release', '=', 1);
        }

        if ($request->query('order_by')) {
            $sort = explode('|', $request->order_by);
            $titleSort = $sort[0];
            $typeSort = $sort[1];
        } else {
            $titleSort = 'created_at';
            $typeSort = 'desc';
        }

        if ($request->query('unverify')) {
            $users->where('active', 0);
        }

        $users = $users->orderBy($titleSort, $typeSort)->paginate(27);

        return view('users.index', compact('users'));
    }

    /**
     * @param User $user
     * @return Factory|View
     */
    public function show(User $user)
    {
        $user->load(
            'shops.earner',
            'earns.shopper',
            'credits.creditable',
            'roles',
            'transactions',
            'receivedFeedbacks.fromUser',
            'givenFeedbacks.toUser',
            'tickets.replies.user'
        );

        return view('users.show')->with([
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    /**
     * @param StoreUser $request
     * @param User $user
     * @return RedirectResponse
     */
    public function store(StoreUser $request, User $user)
    {
        if ($request->filled('admin')) {
            $user->admin = true;
        }

        if ($request->filled('active')) {
            $user->active = true;
        }

        $user->fill($request->except(['admin', 'active']))->save();

        $this->success("User {$user->email} created.");

        return back();
    }

    /**
     * @param User $user
     * @param UpdateUser $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUser $request)
    {
        $data = $request->all();

        if (!$request->filled('password')) {
            unset($data['password']);
        }

        if ($request->filled('active')) {
            $data['active'] = true;
        } else {
            $data['active'] = false;
        }

        if ($request->filled('blocked')) {
            $this->signOutUser($user->id);
            $data['blocked'] = true;
        } else {
            $data['blocked'] = false;
        }

        $user->fill($data);

        foreach ($user->getDirty() as $field => $value) {
            $this->info(
                sprintf("%s changes to %s", Str::humanize($field), $value)
            );
        }

        if ($request->filled('roles')) {
            $user->syncRoles($request->input('roles'));
            $this->info('Permissions updated');
        }

        $user->save();

        return back();
    }

    public function signOutUser(string $userId): void
    {
        DB::table('personal_access_tokens')
        ->where('tokenable_id', $userId)
        ->latest()
        ->delete();
    }

    public function removeVip(Request $request): JsonResponse
    {
        DB::beginTransaction();

        $user = User::query()->findOrFail($request->query('user_id'));

        $credit = $user->credits()->where('type', Credit::TYPE_FAST_RELEASE)->latest()->first();

        if ($user->fast_release) {
            if (optional($credit)->amount < 0) {
                $user->credits()->create([
                    'type' => Credit::TYPE_FAST_RELEASE,
                    'amount' => $credit->amount * -1,
                ]);
                $user->update(['fast_release' => false]);
                DB::commit();
            } else {
                return response()->json(['status' => JsonResponse::HTTP_FORBIDDEN, 'msg' => 'action fail']);
            }
        }

        return response()->json(['status' => JsonResponse::HTTP_OK, 'msg' => 'done']);
    }

    public function sendWalletNotif(Request $request)
    {
        if ($request->get('user_id')) {
            $send = $request->get('notif') == 'true' ? 1 : 0;

            $user = User::query()->where('admin', 1)
                ->find($request->get('user_id'));
            if ($user) {
                $user->update(['send_wallet_notif' => $send]);

                return response()->json([
                    'status' => JsonResponse::HTTP_OK,
                    'msg' => 'Done',
                ]);
            }

            return response()->json([
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'msg' => 'Select Admin User Please!',
            ]);
        }

    }

    public function syncVipWallet(Request $request, CachedSettingsRepository $setting)
    {
        $user = User::query()->where('fast_release', 1)->findOrFail($request->query('user_id'));
        $fastReleaseAmount = $setting->earner($user)->vip;
        $alreadyAmount = -1 * $user->credits()
            ->latest()
            ->where('amount', '<', 0)
            ->where('type', 'fast_release')
            ->first()
            ->amount;

        if ((float) $alreadyAmount == (float) $fastReleaseAmount) {
            return response()->json([
                'msg' => 'Wallet already sync',
                'status' => JsonResponse::HTTP_ACCEPTED,
            ]);
        }

        DB::beginTransaction();

        $user->credits()->create([
            'type' => 'fast_release',
            'amount' => $alreadyAmount,
            'currency' => 'btc',
        ]);

        $user->credits()->create([
            'type' => 'fast_release',
            'amount' => -1 * $fastReleaseAmount,
            'currency' => 'btc',
        ]);
        DB::commit();

        return response()->json([
            'msg' => 'Done',
            'status' => JsonResponse::HTTP_OK,
        ]);
    }

    public function verificationMail(Request $request)
    {
        $user = User::query()->find($request->query('user_id'));
        $token = Str::random(50);
        $user->verification()->create([
            'type' => 'email',
            'source' => $user->email,
            'token' => $token,
        ]);

        Mail::send(new VerificationMail($user, $token));

        return redirect()->back();
    }
}
