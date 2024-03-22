<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Repository\OrderRepositoryInterface;
use App\Services\Invoice\Factory\OrderInvoiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index(OrderRepositoryInterface $order, Request $request)
    {
        $countWaitingForEarnerBtc = $order->countCondition($order::STATUS_PENDING, 'btc');
        $countWaitingForEarnerUsdt = $order->countCondition($order::STATUS_PENDING, 'usdt');

        $waitingForEarnerBtc = $order->getOrderWithCondition($order::STATUS_PENDING, 'btc');
        $waitingForEarnerUsdt = $order->getOrderWithCondition($order::STATUS_PENDING, 'usdt');

        $totalBtcPrice = collect($waitingForEarnerBtc)->map(function ($item) {
            $invoice = OrderInvoiceFactory::from($item)->createInvoice();

            return $invoice->net();
        })->sum();

        $totalUsdtPrice = collect($waitingForEarnerUsdt)->map(function ($item) {
            $invoice = OrderInvoiceFactory::from($item)->createInvoice();

            return $invoice->net();
        })->sum();

        $storageUrl = config('bitoff.bit-storage.url');

        $logs = Log::with('order', 'user')
            ->whereHas('order', function ($query) use ($request) {
                $query->when($request->query('order_id'), function ($q) use ($request) {
                    $q->where('id', $request->query('order_id'));
                });
            })
            ->orderBy('created_at', 'desc');
        if ($request->query('from_date')) {
            $logs->whereDate('created_at', '>=', $request->query('from_date'));
        }
        if ($request->query('to_date')) {
            $logs->whereDate('created_at', '<=', $request->query('to_date'));
        }

        $this->typeFilter($request, $logs);

        $logs = $logs->paginate(35);

        return view('logs.index', compact(
            'logs',
            'countWaitingForEarnerBtc',
            'countWaitingForEarnerUsdt',
            'totalBtcPrice',
            'totalUsdtPrice',
            'order',
            'storageUrl'
        ));
    }

    private function typeFilter(Request $request, Builder $logs)
    {
        if ($request->query('type') == 1) {
            $logs->where('type', 'status')->where('role', 'earner')->where('changes->status', 'cancel');
        }
        if ($request->query('type') == 2) {
            $logs->where('type', 'reserve');
        }
        if ($request->query('type') == 3) {
            $logs->where('type', 'status')->where('role', 'shopper');
        }
        if ($request->query('type') == 4) {
            $logs->where('type', 'item.purchase');
        }
        if ($request->query('type') == 5) {
            $logs->where('type', 'item.purchase.edit');
        }
        if ($request->query('type') == 6) {
            $logs->where('type', 'item.ship');
        }
        if ($request->query('type') == 7) {
            $logs->where('type', 'item.ship.edit');
        }
        if ($request->query('type') == 8) {
            $logs->where('type', 'item.deliver');
        }
        if ($request->query('type') == 9) {
            $logs->where('type', 'status')->where('changes->status', 'wish_pending');
        }
        if ($request->query('type') == 10) {
            $logs->where('type', 'item.cancel');
        }
        if ($request->query('type') == 11) {
            $logs->where('type', 'off');
        }
        if ($request->query('type') == 12) {
            $logs->where('type', 'score');
        }
        if ($request->query('type') == 13) {
            $logs->where('type', 'expire');
        }
        if ($request->query('type') == 14) {
            $logs->where('type', 'support');
        }
        if ($request->query('type') == 15) {
            $logs->where('type', 'support.resolve');
        }
        if ($request->query('type') == 16) {
            $logs->where('type', 'image');
        }
        if ($request->query('type') == 17) {
            $logs->where('type', 'status')->where('changes->status', 'pending');
        }
        if ($request->query('type') == 18) {
            $logs->where('type', 'status')->where('changes->status', 'complete');
        }
        if ($request->query('type') == 19) {
            $logs->where('type', 'status')->where('changes->status', 'issue_founded');
        }
    }
}
