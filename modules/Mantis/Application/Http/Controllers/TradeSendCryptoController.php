<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Notifications\TradeReleased;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Log;
use Throwable;

class TradeSendCryptoController extends Controller
{
    public function __invoke(trade $trade): JsonResponse
    {
        DB::beginTransaction();

        try {
            $trade->update(['status' => Trade::STATUS_RELEASED]);

            $credit = $trade->credits()->make([
                'currency' => $trade->offer_data->currency,
                'type' => $trade->offer_data->is_buy ? Credit::TYPE_SELL_TRADE : Credit::TYPE_BUY_TRADE,
                'amount' => $trade->net_amount,
                'status' => Credit::STATUS_CONFIRMATION,
            ]);

            $toUser = $trade->offer_data->is_buy ? $trade->offer->offerer : $trade->trader;
            $credit->user()->associate($toUser);
            $credit->save();

            DB::commit();

            $this->notifyBuyerAndSeller($trade);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'msg' => 'Crypto sent successfully'
            ], JsonResponse::HTTP_OK);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'msg' => 'Something went wrong.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function notifyBuyerAndSeller(Trade $trade): void
    {
        try {
            $trade->trader->notify((new TradeReleased($trade->hash, $trade->offer->hash))->afterCommit());
            $trade->offer->offerer->notify((new TradeReleased($trade->hash, $trade->offer->hash))->afterCommit());
        } catch (Throwable $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }
    }
}
