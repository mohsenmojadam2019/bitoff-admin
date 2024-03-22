<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderReportService
{
    public function numberOfOrderLastThreeMonth()
    {
        return DB::table('orders')
            ->whereRaw('date(created_at) > ?', [
                now()->addMonths('-3')->format('Y-m-d'),
            ])
            ->count();
    }

    public function numberOfOrderLastMonth()
    {
        return DB::table('orders')
            ->whereRaw('MONTH(created_at) = ?', [
                now()->format('m'),
            ])
            ->count();
    }

    public function numberOfOrderLastWeek()
    {
        return DB::table('orders')
            ->whereRaw('date(created_at) > ?', [
                now()->addDays('-7')->format('Y-m-d'),
            ])->count();
    }

    public function numberOfOrderToDay()
    {
        return DB::table('orders')
            ->whereRaw('date(created_at) = ?', [
                now()->format('Y-m-d'),
            ])->count();
    }

    public function sumTotalPriceOrder()
    {
        return DB::table('orders')
            ->where('status', '<>', 'cancel')
            ->sum('tp');
    }

    public function numberOfOrderItemDelivered()
    {
        return DB::table('order_items')
            ->where('status', '=', 'deliver')
            ->count();
    }

    public function totalWage()
    {
        return DB::table('orders')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['partial_deliver', 'deliver', 'complete'])
            ->where('order_items.status', '<>', 'cancel')
            ->sum('wage');
    }

    public function totalPriceBeforeOff()
    {
        $total = $this->orderReport()->selectRaw('
            sum(item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate)) as btc,
            sum(item_cost + (tax/100) * item_cost) as dollar
       ');
        $this->filter($total);

        return $total->first();
    }

    public function totalPriceNextOff()
    {
        $total = $this->orderReport()->selectRaw('
            sum(item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate) - (item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate)) * (off/100)) as btc,
            sum(item_cost + ((tax/100) * item_cost) - (item_cost + ((tax/100) * item_cost)) * (off/100)) as dollar
       ');
        $this->filter($total);
        if (request()->query('currency') == 'all') {
            return $total->first();
        }

        return $total->where('currency', request()->query('currency', 'btc'))
            ->first();
    }

    public function wage()
    {
        $wage = $this->orderReport()->selectRaw('
            sum((item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate)) * (shopper_wage_percent/100)) as btc,
            sum((item_cost + ((tax/100) * item_cost)) * (shopper_wage_percent/100)) as dollar
       ');

        $this->filter($wage);

        return $wage->first();
    }

    public function escrow()
    {
        $escrow = DB::table('orders')
            ->selectRaw('
                sum(
                    item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate) +
                    (shopper_wage_percent/100) * item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate) -
                    (off/100) * item_cost * bitcoin_rate + ((tax/100) * item_cost * bitcoin_rate)
                ) as btc,
                  sum(
                    item_cost + ((tax/100) * item_cost) +
                    (shopper_wage_percent/100) * item_cost  + ((tax/100) * item_cost ) -
                    (off/100) * item_cost  + ((tax/100) * item_cost )
                ) as dollar
            ')
            ->join(DB::raw("
                    (select sum(price + IFNULL(JSON_EXTRACT(order_items.meta,'$.shipping'),0)  + extra ) as item_cost,order_id from order_items
                    where order_items.status in (?,?,?)
                    group by order_id) as order_details
            "), function ($join) {
                $join->on('order_details.order_id', '=', 'orders.id');
            });

        $escrow->whereRaw('orders.status in (?,?,?,?,?)')->setBindings([
            OrderItem::STATUS_INIT,
            OrderItem::STATUS_SHIP,
            OrderItem::STATUS_PURCHASE,
            Order::STATUS_RESERVE,
            Order::STATUS_SHIP,
            Order::STATUS_PURCHASE,
            Order::STATUS_PARTIAL_SHIP,
            Order::STATUS_PARTIAL_DELIVER,
        ]);

        if (request()->query('currency') && request()->query('currency') != 'all') {
            $escrow->where('currency', request()->query('currency'));
        }
        if (request()->query('from_date')) {
            $escrow->whereRaw('date(orders.created_at) >= ?', [request()->query('from_date')]);
        }

        if (request()->query('to_date')) {
            $escrow->whereRaw('date(orders.created_at) <= ?', [request()->query('to_date')]);
        }

        return $escrow->first();
    }

    public function numberOfOrders()
    {
        $number = DB::table('orders');
        if (request()->query('currency') != 'all') {
            $number->where('currency', request()->query('currency', 'btc'));
        }

        $this->filter($number);

        return $number->count();
    }

    private function orderReport()
    {
        $orders = DB::table('orders')
            ->join(DB::raw("
                    (select sum(price + IFNULL(JSON_EXTRACT(order_items.meta,'$.shipping'),0)  + extra ) as item_cost,order_id from order_items
                    where order_items.status <> 'cancel'
                    group by order_id) as order_details
            "), function ($join) {
                $join->on('order_details.order_id', '=', 'orders.id');
            });

        if (request()->query('currency') != 'all') {
            return $orders->where('currency', request()->query('currency', 'btc'));
        }

        return $orders;
    }

    private function filter($data)
    {
        if (request()->query('status')) {
            $data->whereRaw('orders.status = ?', [request()->query('status')]);
        }
        if (request()->query('from_date')) {
            $data->whereRaw('date(orders.created_at) >= ?', [request()->query('from_date')]);
        }

        if (request()->query('to_date')) {
            $data->whereRaw('date(orders.created_at) <= ?', [request()->query('to_date')]);
        }
    }
}
