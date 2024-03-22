<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsernameRequest;
use App\Models\User;
use App\Models\Username;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsernamesController extends Controller
{
    public function index(Request $request)
    {
        $username = Username::query()->find($request->query('username_id'));
        $user = User::query()->findOrFail($request->query('user_id'));

        $usernames = Username::query()
            ->where('user_id', $request->query('user_id'))
            ->latest()
            ->get();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'data' => view('users.partials.usernames', compact('username', 'user', 'usernames'))->render(),
        ]);
    }

    public function store(UsernameRequest $request)
    {
        $user = User::query()->find($request->user_id);

        DB::table('usernames')->insertOrIgnore([
            'username' => $user->username,
            'user_id' => $user->id,
            'main_user'=>1
        ]);

        Username::query()->updateOrCreate(['id' => $request->username_id], [
            'username' => $request->username,
            'user_id' => $request->user_id
        ]);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'msg' => trans('message.success-store'),
            'data' => [
                'url' => route('usernames.index') . '?user_id=' . $request->user_id
            ]
        ]);
    }
}
