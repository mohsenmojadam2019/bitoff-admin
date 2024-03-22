<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use App\Support\Hash\HashId;
use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = Trade::with('trader','offer.offerer','offer.paymentMethod')->when($request->query('id'), function ($q, $tradeId) {
            $q->where('id', HashId::decode($tradeId));
            })
            ->when($request->filled('isBuy'), function ($q) use ($request) {
                $q->whereHas('offer', function ($q) use ($request) {
                    $q->where('is_buy', $request->isBuy ? 0 : 1);
                });
            })
            ->when($request->query('currency'), function ($q, $currency) {
                $q->whereHas('offer', function ($q) use ($currency) {
                    $q->where('currency', $currency);
                });
            })
            ->when($request->query('status'), function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->query('trader'), function ($q, $trader) {
                $q->whereHas('trader', function ($q) use ($trader) {
                    if (Str::startsWith($trader, '@')) {
                        $q->where('username', str_replace('@', '', $trader));
                    } else {
                        $q->where('email', $trader);
                    }
                });
            })
            ->when($request->query('offerer'), function ($q, $offerer) {
                $q->whereHas('offer', function ($q) use ($offerer) {
                    $q->whereHas('offerer', function ($q) use ($offerer) {
                        if (Str::startsWith($offerer, '@')) {
                            $q->where('username', str_replace('@', '', $offerer));
                        } else {
                            $q->where('email', $offerer);
                        }
                    });
                });
            })
            ->when($request->query('from_date'), function ($q, $fromDate) {
                $q->where('created_at', '>=', $fromDate);
            })
            ->when($request->query('to_date'), function ($q, $toDate) {
                $q->where('created_at', '<=', $toDate);
            })->orderBy('created_at','desc')
            ->paginate();

        return view('Mantis::trades.index', compact('trades'));
    }

    public function show(Trade $trade)
    {
        return view('Mantis::trades.show', compact('trade'));
    }

    public function overview(Trade $trade)
    {
        $disputeData = null;

        if ($trade->status == Trade::STATUS_DISPUTE) {
            $disputeData = $trade->tradeReason()
                ->where('trade_status', Trade::STATUS_DISPUTE)
                ->latest('created_at')
                ->first() ?? null;
        }

        return $this->ajaxResponse(view('Mantis::trades.partials.overview', compact('trade', 'disputeData'))
            ->render());
    }

    public function offerer(Trade $trade)
    {
        $offerer = $trade->offer->offerer;

        return $this->ajaxResponse(view('Mantis::trades.partials.offerer', compact('offerer'))
                    ->render());
    }

    public function trader(Trade $trade)
    {
        $trader = $trade->trader;

        return $this->ajaxResponse(view('Mantis::trades.partials.trader', compact('trader'))
                    ->render());
    }

    public function history(Trade $trade)
    {
        $activities = $trade->activities ?? null;
        return $this->ajaxResponse(view('Mantis::trades.partials.history', compact('trade', 'activities'))
                    ->render());
    }

    public function credits(Trade $trade)
    {
        $credits =$trade->credits()->with('creditable','user')->paginate();

        return $this->ajaxResponse(view('Mantis::trades.partials.credits', compact('credits'))
                    ->render());
    }

    public function tickets()
    {
        return $this->ajaxResponse(view('Mantis::trades.partials.tickets')
                    ->render());
    }
}
