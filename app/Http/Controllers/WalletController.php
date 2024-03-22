<?php

namespace App\Http\Controllers;

use App\Api\SyncWalletApi;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function syncBtc(Request $request, SyncWalletApi $sync)
    {
        $user = User::query()->find($request->query('user_id'));
        $wallet = $user->wallets()->where('currency', Wallet::CURRENCY_BTC)->first();

        if (!$wallet) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'msg' => 'user address not found',
            ]);
        }

        $sync->address($wallet->address)->type('bitcoin')->send();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'msg' => 'Sync request sent for bitcoin',
        ]);
    }

    public function syncUsdt(Request $request, SyncWalletApi $sync)
    {
        $user = User::query()->find($request->query('user_id'));
        $wallet = $user->wallets()->where('currency', Wallet::CURRENCY_USDT)->first();

        if (!$wallet) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'msg' => 'user address not found',
            ]);
        }

        $sync->address($wallet->address)->type('usdt')->send();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'msg' => 'Sync request sent for usdt',
        ]);
    }
}
