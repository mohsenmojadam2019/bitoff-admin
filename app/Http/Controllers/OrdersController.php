<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTrackingRequest;
use App\Mail\ChatMail;
use App\Models\Chat;
use App\Models\Credit;
use App\Models\Item;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ItemCanceled;
use App\Notifications\OrderCanceled;
use App\Services\BitCoinRate;
use App\Services\Invoice\Factory\OrderInvoiceFactory;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrdersController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $orders = Order::where(function ($query) use ($request) {
            if ($request->id) {
                $query->find($request->id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->query('from_date')) {
                $query->whereDate('created_at', '>=', $request->query('from_date'));
            }

            if ($request->query('to_date')) {
                $query->whereDate('created_at', '<=', $request->query('to_date'));
            }

            if ($request->has('issue')) {
                $query->where('status', Order::STATUS_PENDING)
                    ->whereIn('id', function ($query) {
                        $query->select('order_id')->from('order_logs')->where('changes->status', 'cancel');
                    })
                    ->whereNull('earner_id');
            }

            if ((bool) $request->get('support')) {
                $query->where('support', 1);
            }

            if ($request->fast) {
                $query->where('fast_release', 1);
            }

            if ($request->shopper) {
                $query->whereHas('shopper', function ($q) use ($request) {
                    if (substr($request->shopper, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->shopper));
                    } elseif (is_numeric($request->shopper)) {
                        $q->where('mobile', $request->shopper);
                    } else {
                        $q->where('email', $request->shopper);
                    }
                });
            }

            if ($request->earner) {
                $query->whereHas('earner', function ($q) use ($request) {
                    if (substr($request->earner, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->earner));
                    } elseif (is_numeric($request->earner)) {
                        $q->where('mobile', $request->earner);
                    } else {
                        $q->where('email', $request->earner);
                    }
                });
            }

            if ($request->currency) {
                $query->where('currency', $request->currency);
            }
        })
            ->with('earner', 'shopper', 'items.product')
            ->withCount('activities')
            ->orderByDesc('id')
            ->paginate(15);

        $usdt = Credit::CURRENCY_USDT;
        $btc = Credit::CURRENCY_BTC;

        return view('orders.index', compact('orders', 'usdt', 'btc'));
    }

    /**
     * @return Factory|View
     */
    public function show(Order $order)
    {

        $order->load(
            'items.product',
            'earner',
            'shopper',
            'logs.user',
            'feedbacks',
            'reservation.credits',
            'reservation.tracks.items',
            'tickets.replies.user',
            'wishes'
        );

        if ($order->source != 'bitoff') {
            $order->load('reservation.images');
        }

        $reserve = Reservation::query()->where('order_id', $order->id)->get()->pluck('id')->toArray();
        $messages = [];
        if ($reserve) {
            $chats = $order->chats()->whereIn('reserve_id', $reserve)->get()->each(function ($chat) use (&$messages) {
                if (optional($chat)->messages) {

                    foreach ($chat->messages as $message) {
                        $messages[] = [
                            'username' => User::query()->find($message['user_id'])->username,
                            'from' => $message['from'],
                            'message' => $message['message'],
                            'created_at' => $message['created_at'],
                        ];
                    }
                }
            });
        } else {
            $chats = [];
        }
        $support = null;
        if ($order->support) {
            $support = $order->logs->where('reserve_id', $order->reserve_id)->where('type', 'support')->first();
        }

        $invoice = $order->getInvoice();

        return view('orders.old_show', [
            'order' => $order,
            'chats' => $chats,
            'support' => $support,
            'invoice' => $invoice,
            'messages' => $messages,
        ]);
    }

    public function removeEarner(Order $order)
    {
        $data = request()->validate(['description' => 'required']);

        if (! $order->earner_id) {
            return response()->json(['message' => 'There is no earner'], 403);
        }

        if (! $order->isState(
            Order::STATUS_RESERVE,
            Order::STATUS_PURCHASE,
            Order::STATUS_SHIP,
            Order::STATUS_PARTIAL_SHIP,
            Order::STATUS_PARTIAL_DELIVER,
            Order::STATUS_ISSUE_FOUNDED
        )) {
            return response()->json(['message' => 'Cancel is not available now'], 403);
        }

        $changes = ['description' => $data['description']];

        DB::beginTransaction();

        if (! $order->items->whereNotIn('status', [Item::STATUS_DELIVER, Item::STATUS_CANCEL])->count()) {
            $credit = $order->reservation->credits()
                ->where('type', Credit::TYPE_SHOP)
                ->where('user_id', $order->shopper_id)
                ->firstOrFail();

            $order->reservation->storeCredit([
                'user_id' => $order->shopper_id,
                'type' => Credit::TYPE_CANCEL,
                'amount' => abs($credit->amount),
            ]);

            $order->toState(Order::STATUS_CANCEL, true, false)->storeLog([
                'type' => 'cancel',
                'role' => 'admin',
                'user_id' => $this->user->id,
                'reserve_id' => $order->reserve_id,
                'changes' => array_merge($changes, [
                    'credit' => $credit->id,
                ]),
            ]);

            $order->removeEarner()->freshItems();

            if ($order->shopper->hasEnoughCreditFor($order)) {
                $order->toState(Order::STATUS_PENDING);
            } else {
                $order->toState(Order::STATUS_CREDIT_PENDING);
            }
        } else {
            $items = $order->items->whereNotIn('status', [Item::STATUS_DELIVER, Item::STATUS_CANCEL]);
            $invoice = OrderInvoiceFactory::from($order)
                ->setItems($items)
                ->createInvoice();
            $credit = $order->reservation->storeCredit([
                'user_id' => $order->shopper_id,
                'type' => 'cancel',
                'amount' => $invoice->btc($order->bitcoin_rate)->net(),
            ]);

            $order->toState(Order::STATUS_CANCEL, true, false)->storeLog([
                'type' => 'status',
                'role' => 'admin',
                'user_id' => $this->user->id,
                'reserve_id' => $order->reserve_id,
                'changes' => array_merge($changes, [
                    'status' => Order::STATUS_CANCEL,
                    'credit' => $credit->id,
                ]),
            ]);

            Item::whereIn('id', $items->pluck('id')->toArray())
                ->update(['status' => Item::STATUS_CANCEL]);

            if ($order->items()->where('status', Item::STATUS_DELIVER)->count()) {
                $order->toState(Order::STATUS_DELIVER, true, false)->storeLog([
                    'type' => 'status',
                    'reserve_id' => $order->reserve_id,
                    'changes' => [
                        'status' => Order::STATUS_DELIVER,
                    ],
                ]);
            } elseif ($order->shopper->hasEnoughCreditFor($order)) {
                $order->toState(Order::STATUS_PENDING);
                $order->removeEarner()->freshItems();
            } else {
                $order->toState(Order::STATUS_CREDIT_PENDING);
                $order->removeEarner()->freshItems();
            }
        }
        $order->shopper->syncOrdersAsShopper();
        DB::commit();

        return response()->json([]);
    }

    /**
     * @return RedirectResponse
     */
    public function cancelItem(string $orderId, $itemId)
    {
        DB::beginTransaction();

        $order = Order::lockForUpdate()->findOrFail($orderId);

        if (! $order->earner_id) {
            $this->error('Cannot update order information, No one reserved the order');

            return back();
        }

        $item = $order->items()->findOrFail($itemId);

        if ($item->status == Item::STATUS_CANCEL) {
            $this->warning('Item already canceled.');

            return back();
        }

        if ($item->status == Item::STATUS_DELIVER) {
            $this->warning('Cannot cancel a delivered item');

            return back();
        }

        $item->update(['status' => Item::STATUS_CANCEL]);

        $invoice = OrderInvoiceFactory::from($order)
            ->setItems($order->items->where('id', $item->id))
            ->createInvoice();
        $earner = $order->earner;

        if ($order->currency === 'btc') {
            $amount = $invoice->btc($order->bitcoin_rate)->net();
        } else {
            $amount = $invoice->net();
        }

        $credit = $order->reservation->credits()->create([
            'type' => Credit::TYPE_CANCEL,
            'currency' => $order->currency,
            'amount' => $amount,
            'user_id' => $order->shopper->id,
            'extra' => ['order_item_id' => $item->id],
        ]);

        $order->storeLog([
            'type' => 'item.cancel',
            'user_id' => $this->user->id,
            'role' => 'admin',
            'changes' => [
                'item' => $item->id,
                'product' => $item->product_id,
                'credit' => $credit->id,
            ],
        ]);

        $order->shopper->syncOrdersAsShopper();

        $canceled = $order->items()->where('status', Item::STATUS_CANCEL)->count();
        $delivered = $order->items()->where('status', Item::STATUS_DELIVER)->count();
        $totalCancel = false;
        if ($order->items()->count() == $canceled + $delivered) {
            $order->update(['status' => Order::STATUS_DELIVER]);
        }
        if ($canceled === $order->items()->count()) {
            $totalCancel = true;
            $order->removeEarner()->freshItems();

            $openOrdersAmount = $order->shopper->getOpenOrdersAmount($order->currency, app(BitCoinRate::class)->getValue());

            if ($order->shopper->hasEnoughCreditFor($order) && (($openOrdersAmount + $order->net) <= $order->shopper->getCreditSum($order->currency))) {
                $order->toState(Order::STATUS_PENDING);
            } else {
                $order->toState(Order::STATUS_CREDIT_PENDING);
            }
        }
        DB::commit();
        $this->info("Item #{$item->id} has been removed");

        if ($totalCancel) {
            $this->warning('Earner kicked out');
        }

        $order->shopper->notify(
            (new ItemCanceled($order, $item, $order->shopper->username))->onQueue('order')
        );

        $earner->notify(
            (new ItemCanceled($order, $item, $earner->username))->onQueue('order')
        );

        return response()->json([], 204);
    }

    /**
     * @return RedirectResponse
     */
    public function updateTracking(Order $order, $itemId, UpdateTrackingRequest $request)
    {
        $item = $order->items()->findOrFail($itemId);
        $item->amazon_order_id = $request->get('amazon_order_id');
        $item->tracking = $request->get('tracking');
        $data = $item->getDirty();
        $item->save();

        if (isset($data['tracking'])) {
            $order->storeLog([
                'user_id' => $this->user->id,
                'role' => 'admin',
                'type' => 'item.track',
                'changes' => [
                    'item' => $item->id,
                    'track' => $request->input('tracking'),
                ],
            ]);
        }

        if (isset($data['amazon_order_id'])) {
            $order->storeLog([
                'user_id' => $this->user->id,
                'role' => 'admin',
                'type' => 'item.purchase',
                'changes' => [
                    'item' => $item->id,
                    'id' => $request->input('amazon_order_id'),
                ],
            ]);
        }

        $this->info('Tracking detail has been updated successfully.');

        return back();
    }

    public function storeChat(int $id, int $reserve, Request $request)
    {
        $request->validate(['message' => 'required']);
        $chat = Chat::query()
            ->where('order_id', $id)
            ->where('reserve_id', $reserve)
            ->first(['_id', 'unread_shopper', 'unread_earner']);

        if (! $chat) {
            $chat = Chat::query()
                ->create(['order_id' => $id, 'reserve_id' => $reserve]);
        }

        $message = [
            'from' => 'admin',
            'user_id' => $this->user->id,
            'message' => $request->get('message'),
            'created_at' => $now = Carbon::now()->toDateTimeString(),
        ];

        $chat->push('messages', [$message]);
        $chat->unread_shopper++;
        $chat->unread_earner++;
        $chat->save();

        $this->success('Chat updated');

        $receiver = User::query()->find($request->input('receiver'));
        $order = Order::query()->find($id);
        Mail::queue((new ChatMail($receiver, $order))->onQueue('order'));

        return redirect()->back();
    }

    public function deliver($id, $itemId)
    {
        /** @var Order $order */
        $order = Order::status([Order::STATUS_PURCHASE, Order::STATUS_PARTIAL_SHIP, Order::STATUS_SHIP, Order::STATUS_PARTIAL_DELIVER])
            ->whereNotNull('earner_id')
            ->findOrFail($id);

        /** @var Item $item */
        $item = $order->items()
            ->whereIn('status', [Item::STATUS_PURCHASE, Item::STATUS_SHIP])
            ->findOrFail($itemId);

        DB::beginTransaction();

        try {
            $item->update(['status' => Item::STATUS_DELIVER]);

            $credit = $order->reservation->creditFor($order->earner, [
                'type' => Credit::TYPE_EARN,
                'amount' => $item->netForEarner(),
                'extra' => ['order_item_id' => $item->id],
                'currency' => $order->currency,
            ]);

            $order->storeLog([
                'type' => 'item.deliver',
                'reserve_id' => $order->reserve_id,
                'role' => 'admin',
                'user_id' => $this->user->id,
                'changes' => [
                    'item' => $item->id,
                    'product' => $item->product_id,
                    'credit' => $credit->id,
                ],
            ]);

            $complete = $order->items()->whereIn('status', [
                Item::STATUS_DELIVER,
                Item::STATUS_CANCEL,
            ]);

            // Deliver order if all of the items are delivered.
            $state = null;
            if ($order->items()->count() == $complete->count()) {
                $state = Order::STATUS_DELIVER;
            } elseif (! $order->isState(Order::STATUS_PARTIAL_DELIVER)) {
                $state = Order::STATUS_PARTIAL_DELIVER;
            }

            if ($state) {
                $order->toState($state, true, false)->storeLog([
                    'reserve_id' => $order->reserve_id,
                    'type' => 'status',
                    'changes' => [
                        'status' => $state,
                    ],
                ]);
            }
            DB::commit();

            return response()->json([], 204);
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function tracking($id, $itemId)
    {
        $data = request()->validate(['tracking' => ['required', 'url']]);

        /** @var Order $order */
        $order = Order::status([Order::STATUS_PURCHASE, Order::STATUS_SHIP, Order::STATUS_PARTIAL_SHIP, Order::STATUS_PARTIAL_DELIVER])
            ->findOrFail($id);

        $item = $order->items()
            ->whereIn('status', [Item::STATUS_PURCHASE, Item::STATUS_SHIP])
            ->findOrFail($itemId);

        DB::beginTransaction();

        try {
            $item->update([
                'tracking' => $data['tracking'],
                'status' => Item::STATUS_SHIP,
            ]);

            $order->storeLog([
                'type' => 'item.ship',
                'reserve_id' => $order->reserve_id,
                'role' => 'admin',
                'user_id' => $this->user->id,
                'changes' => [
                    'item' => $item->id,
                    'product' => $item->product_id,
                    'track' => $data['tracking'],
                ],
            ]);

            // handling state
            $shippedItems = $order->items()
                ->where('status', Item::STATUS_SHIP)
                ->count();

            $all = $order->items()
                ->where('status', '!=', Item::STATUS_CANCEL)
                ->count();

            $newState = null;
            if ($order->isState(Order::STATUS_RESERVE, Order::STATUS_PURCHASE, Order::STATUS_PARTIAL_SHIP) && $shippedItems == $all) {
                $newState = Order::STATUS_SHIP;
            } elseif ($order->isState(Order::STATUS_PURCHASE)) {
                $newState = Order::STATUS_PARTIAL_SHIP;
            }

            if ($newState) {
                $order->toState($newState, true, false)->storeLog([
                    'reserve_id' => $order->reserve_id,
                    'type' => 'status',
                    'changes' => [
                        'status' => $newState,
                    ],
                ]);
            }

            DB::commit();

            return response()->json();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        $data = request()->validate(['description' => 'required']);

        $order = Order::status([
            Order::STATUS_PENDING,
            Order::STATUS_CREDIT_PENDING,
            Order::STATUS_WISH_PENDING,
            Order::STATUS_WISH_CALLBACK,
            Order::STATUS_ISSUE_FOUNDED,
        ])
            ->findOrFail($id);

        $order->toState(Order::STATUS_CANCEL, true, false)->storeLog([
            'user_id' => $this->user->id,
            'role' => 'admin',
            'type' => 'cancel',
            'changes' => [
                'description' => $data['description'],
            ],
        ]);

        $order->shopper->notify(
            (new OrderCanceled($order, $order->shopper->username))->onQueue('order')
        );

        return response()->json([], 204);
    }

    public function resolve($id)
    {
        $order = Order::where('support', 1)->findOrFail($id);
        $order->update(['support' => 0]);
        $order->storeLog([
            'user_id' => $this->user->id,
            'role' => 'admin',
            'type' => 'support.resolve',
            'changes' => [],
        ]);

        return response()->json();
    }
}
