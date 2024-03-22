<?php

namespace Bitoff\Syncer\AcceptedUnits;

use App\Models\Order;
use App\Models\User;
use App\Services\BitCoinRate;
use Bitoff\Feedback\Utilities\Calculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderSyncerAccepted implements SyncerAcceptedInterface
{
    private BitCoinRate $btcRate;
    private Collection $orders;
    private Model|User $user;

    public function __construct()
    {
        $this->btcRate = app(BitCoinRate::class);
        $this->orders = collect();
    }

    public function getActives(string $currency): Collection
    {
        $this->orders = Order::where('shopper_id', $this->user->id)
            ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_WISH_PENDING])
            ->where('currency', $currency)
            ->get(['id', 'currency', 'created_at', 'tp', 'shopper_wage_percent', 'off', 'source']);

        return $this->mapping();
    }

    public function getInActives(string $currency): Collection
    {
        $this->orders = Order::where('shopper_id', $this->user->id)
            ->where('status', Order::STATUS_CREDIT_PENDING)
            ->where('currency', $currency)
            ->get(['id', 'currency', 'created_at', 'tp', 'shopper_wage_percent', 'off', 'source']);

        return $this->mapping();
    }

    private function mapping(): Collection
    {
        return $this->orders->map(fn(Order $order) => [
            'id' => $order->id,
            'type' => Order::class,
            'currency' => $order->currency,
            'created_at' => $order->created_at->timestamp,
            'amount' => $this->calculateOrderNet($order),
        ]);
    }

    public function verifierForEarnedAmount(int $id): void
    {
        $order = $this->findOrder($id);

        if ($order->isNative() && !$order->wishes()->exists()) {
            $order->wishes()->create([])->dispatch();
            $order->toState(Order::STATUS_WISH_PENDING);
        } else {
            $order->toState(Order::STATUS_PENDING);
        }
    }

    public function verifierForLoseAmount(int $id): void
    {
        $order = $this->findOrder($id);
        $order->toState(Order::STATUS_CREDIT_PENDING);
    }

    /**
     * @param Order $order
     * @return float
     */
    private function calculateOrderNet(Order $order): float
    {
        $orderNet = (float) ($order->tp + Calculator::percent($order->shopper_wage_percent, $order->tp) - Calculator::percent($order->off, $order->tp));
        return $orderNet * ($order->currency === 'usdt' ? 1.0 :  $this->btcRate->getValue());
    }

    /**
     * @param $orderId
     * @return Order
     */
    private function findOrder($orderId): Order
    {
        $this->orders = $this->orders->keyBy('id');
        return $this->orders->get($orderId);
    }

    public function setUser(Model|User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
