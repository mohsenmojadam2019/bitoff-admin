<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Http\Requests\TradeCancelRequest;
use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Models\TradeReason;
use Bitoff\Mantis\Application\Notifications\TradeCanceled;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Log;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class TradeCancelController extends Controller
{
    public function __invoke(Trade $trade, TradeCancelRequest $request): JsonResponse
    {
        if ($trade->isStatus(Trade::STATUS_CANCELED)) {
            throw new BadRequestException("This trade already canceled", JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();
            $trade->disableLogging();

            $trade->update(['status' => Trade::STATUS_CANCELED]);

            $credit = $trade->credits()->make([
                'currency' => $trade->offer_data->currency,
                'type' => Credit::TYPE_CANCEL_TRADE,
                'amount' => $trade->net_amount + $trade->fee,
                'status' => Credit::STATUS_CONFIRMATION,
            ]);

            $trade->tradeReason()->save(TradeReason::make([
                'trade_status' => Trade::STATUS_CANCELED,
                'reason' => $request->get('reason'),
                'causer' => 'admin',
                'user_id' => auth()->user()->id,
            ]));

            $credit->user()->associate($trade->offer_data->is_buy ? $trade->trader : $trade->offer->offerer);
            $credit->save();

            $this->logTradeCanceled($trade, $request->get('reason'));
            DB::commit();

            $this->notifyBuyerAndSeller($trade);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'msg' => 'Trade canceled successfully'
            ], JsonResponse::HTTP_OK);

        } catch (Throwable $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    private function notifyBuyerAndSeller(Trade $trade): void
    {
        try {
            $trade->trader->notify((new TradeCanceled($trade->hash, $trade->offer->hash))->afterCommit());
            $trade->offer->offerer->notify((new TradeCanceled($trade->hash, $trade->offer->hash))->afterCommit());
        } catch (Throwable $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
        }
    }

    private function logTradeCanceled(Trade $trade, string $reason): void
    {
        activity()
            ->performedOn($trade)
            ->withProperties([
                'old' => ['status' => $trade->status],
                'attributes' => ['status' => Trade::STATUS_CANCELED],
                'reason' => $reason
            ])
            ->tap(function (Activity $activity) {
                $activity->log_name = Trade::class;
            })
            ->log('updated');
    }
}
