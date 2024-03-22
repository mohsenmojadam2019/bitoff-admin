<?php

namespace App\Services\Invoice\Factory;

use App\Models\Item;
use App\Models\Order;
use App\Services\Invoice\Invoice;

class OrderInvoiceFactory extends InvoiceFactoryTemplate implements InvoiceFactoryInterface
{
    /** @var Order */
    protected $order;

    /** @var Item [] */
    protected $items;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public static function from(Order $order)
    {
        return new static($order);
    }

    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    protected function buildInstance()
    {
        $this->instance = (new Invoice)
            ->setTaxPercent($this->order->tax)
            ->setOffPercent($this->order->off)
            ->setWagePercent($this->order->shopper_wage_percent)
            ->setEarnerWagePercent($this->order->earner_wage_percent);

        return $this;
    }

    protected function hasManualItems()
    {
        return $this->items !== null;
    }

    protected function getItems()
    {
        return $this->items ?: $this->order->items;
    }

    protected function buildItems()
    {
        if (! is_a($this->instance, Invoice::class)) {
            throw new \RuntimeException('Invoice does not exists in object');
        }

        foreach ($this->getItems() as $item) {
            $this->instance->createItem(
                $item->id,
                $item->price,
                $item->meta ?: [],
                1,
                $this->hasManualItems() ? true : $item->status !== Item::STATUS_CANCEL
            );
        }

        return $this;
    }
}
