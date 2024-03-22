<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcAccepted($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'reserve'
            group by order_id
            ) as reserve
        "), 'reserve.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtAccepted($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'reserve'
            group by order_id
            ) as reserve
        "), 'reserve.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersWithBtcCurrency($request)
    {
        $orders = DB::table('orders')
            ->selectRaw('count(id) as numberOfStatus,status')
            ->where('currency', Order::CURRENCY_BTC)
            ->whereIn('status', $this->orderStatus())
            ->groupBy('status');

        $this->filter($orders, $request);

        return $orders->get();
    }

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersWithUsdtCurrency($request)
    {
        $orders = DB::table('orders')
            ->selectRaw('count(id) as numberOfStatus,status')
            ->where('currency', Order::CURRENCY_USDT)
            ->whereIn('status', $this->orderStatus())
            ->groupBy('status');

        $this->filter($orders, $request);

        return $orders->get();
    }

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcWaitForEarner($request)
    {
        $orders = DB::table('orders')
            ->where('status', Order::STATUS_PENDING)
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtWaitForEarner($request)
    {
        $orders = DB::table('orders')
            ->where('status', Order::STATUS_PENDING)
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcByEarnerSupported($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support' and role = 'earner'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtByEarnerSupported($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support' and role = 'earner'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcByShopperSupported($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support' and role = 'shopper'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtByShopperSupported($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support' and role = 'shopper'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcSupportedAndResolved($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC)
            ->whereRaw("id in (select order_id from order_logs where type='support.resolve')");

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtSupportedAndResolved($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'support'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT)
            ->whereRaw("id in (select order_id from order_logs where type='support.resolve')");

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersBtcCanceled($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'cancel'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Collection
     */
    public function ordersUsdtCanceled($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select order_id from order_logs
            where type = 'cancel'
            group by order_id
            ) as support
        "), 'support.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersBtcIssueFounded($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select count(*) as issueCount,order_id from order_logs
            where changes->>'$.status' = 'issue_founded'
            group by order_logs.order_id
        ) as issue
        "), 'issue.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_BTC);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersUsdtIssueFounded($request)
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
        (
            select count(*) as issueCount,order_id from order_logs
            where changes->>'$.status' = 'issue_founded'
            group by order_logs.order_id
        ) as issue
        "), 'issue.order_id', '=', 'orders.id')
            ->where('currency', Order::CURRENCY_USDT);

        $this->filter($orders, $request);

        return $orders->count();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $orders
     * @param \Illuminate\Http\Request           $request
     *
     * @return viod
     */
    private function filter($orders, $request)
    {
        $from = $request->query('from') ?: now()->format('Y-m-d');
        $to = $request->query('to') ?: now()->format('Y-m-d');

        $orders->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
    }

    private function orderStatus(): array
    {
        return [
            Order::STATUS_SHIP,
            Order::STATUS_PURCHASE,
            Order::STATUS_DELIVER,
            Order::STATUS_PARTIAL_DELIVER,
            Order::STATUS_COMPLETE,
            Order::STATUS_PARTIAL_SHIP,
            Order::STATUS_CANCEL,
        ];
    }
}
