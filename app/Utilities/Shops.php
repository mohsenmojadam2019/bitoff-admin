<?php

namespace App\Utilities;

use App\Models\Credit;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Shops
{
    /**
     * @return HasMany
     */
    public function shopperOrders()
    {
        return $this->hasMany(Order::class, 'shopper_id');
    }

    /**
     * @return int
     */
    public function getShopScoreAttribute()
    {
//        if (
//            $this->purseInfo
//            && $this->purseInfo->should_merge_score
//            && (($this->purseInfo->shopper_level > $this->purseInfo->earner_level)
//                || ($this->purseInfo->shopper_level == $this->purseInfo->earner_level))
//        ) {
//            return $this->scores()->where('role', 'shopper')->sum('rate') + $this->purseInfo->score;
//        }
//
//        return $this->scores()->where('role', 'shopper')->sum('rate');
//        return 50;
    }

    /**
     * @return HasMany
     */
    public function openShopperOrders()
    {
        return $this->shopperOrders()->whereIn('status', [
            Order::STATUS_WISH_PENDING,
            Order::STATUS_CREDIT_PENDING,
            Order::STATUS_PENDING,
            Order::STATUS_RESERVE,
        ]);
    }

    /**
     * Change order status to credit pending if their net is more than user's total credit,
     * Change order status to pending if their net is less than user's total credit.
     */
    public function syncOrdersAsShopper()
    {
    }

    public function getOpenOrdersAmount(string $currency, float $rate = 1): float
    {
        $column = 'open_order_sum';
        $selectQuery = "SUM( tp + (( shopper_wage_percent / 100 ) * tp) - (( off / 100 ) * tp) ) AS {$column}";
        $orders = $this->shopperOrders()
            ->selectRaw($selectQuery)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_WISH_PENDING, Order::STATUS_WISH_CALLBACK])
            ->where('currency', $currency)
            ->first();

        $sum = $orders->{$column} ?? 0;

        return (float) ($sum * $rate);
    }

    public function checkOrderCanBePending(Order $order, float &$openOrdersAmount, $creditSum, ?float $rate = 1): void
    {
        $orderNet = (float) ($order->tp + Calculator::percent($order->shopper_wage_percent, $order->tp) - Calculator::percent($order->off, $order->tp));
        $orderNet *= $rate;

        if ($order->isState(Order::STATUS_CREDIT_PENDING) && (($openOrdersAmount + $orderNet) <= $creditSum)) {
            if ($order->isNative() && ! $order->wishes()->exists()) {
                $order->wishes()->create([])->dispatch();
                $order->toState(Order::STATUS_WISH_PENDING);
            } else {
                $order->toState(Order::STATUS_PENDING);
            }

            $openOrdersAmount += $orderNet;
        } elseif ($order->isState(Order::STATUS_PENDING) && ($openOrdersAmount >= $creditSum)) {
            $order->toState(Order::STATUS_CREDIT_PENDING);
            $openOrdersAmount -= $orderNet;
        }
    }
}
