<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Models\Credit;
use Bitoff\Mantis\Application\Models\Offer;
use Bitoff\Mantis\Application\Models\Trade;
use Bitoff\Mantis\Application\Support\Hash\HashId;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $offers = Offer::with('offerer','paymentMethod')->when($request->query('id'), function ($q, $offerId) {
                $q->where('id', HashId::decode($offerId));
            })
            ->when($request->filled('isBuy'), function ($q) use ($request) {
                $q->where('is_buy', $request->isBuy);
            })
            ->when($request->query('currency'), function ($q, $currency) {
                $q->where('currency', $currency);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('active', $request->status);
            })
            ->when($request->query('offerer'), function ($q, $offerer) {
                $q->whereHas('offerer', function ($q) use ($offerer) {
                    if (Str::startsWith($offerer, '@')) {
                        $q->where('username', str_replace('@', '', $offerer));
                    } else {
                        $q->where('email', $offerer);
                    }
                });
            })
            ->when($request->query('from_date'), function ($q, $fromDate) {
                $q->where('created_at', '>=', $fromDate);
            })
            ->when($request->query('to_date'), function ($q, $toDate) {
                $q->where('created_at', '<=', $toDate);
            })
            ->paginate();

        return view('Mantis::offers.index', compact('offers'));
    }

    public function show(Offer $offer)
    {
        return view('Mantis::offers.show', compact('offer'));
    }

    public function overview(Offer $offer)
    {
        return $this->ajaxResponse(view('Mantis::offers.partials.overview', compact('offer'))
                    ->render());
    }

    public function offerer(Offer $offer)
    {
        $offerer = $offer->offerer;

        return $this->ajaxResponse(view('Mantis::offers.partials.offerer', compact('offerer'))
                    ->render());
    }

    public function trades(Offer $offer)
    {
        $trades = $offer->trades()->with('trader')->paginate();

        return $this->ajaxResponse(view('Mantis::offers.partials.trades', compact('trades'))
                    ->render());
    }

    public function credits(Offer $offer)
    {
        $credits = Credit::with('creditable','user')
        ->where('creditable_type', Trade::class)
        ->whereIn('creditable_id', function ($query) use ($offer) {
            $query->select('id')
            ->from('trades')
            ->where('offer_id', $offer->id);
        })->paginate();

        return $this->ajaxResponse(view('Mantis::offers.partials.credits', compact('credits'))
            ->render());
    }

    public function history(Offer $offer)
    {
        $activities = $offer->activities ?? null;
        return $this->ajaxResponse(view('Mantis::offers.partials.history', compact('offer', 'activities'))
                    ->render());
    }
}
