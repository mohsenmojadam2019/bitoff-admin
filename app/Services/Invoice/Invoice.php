<?php

namespace App\Services\Invoice;

use App\Support\Calculator;

class Invoice
{
    protected $offPercent;

    protected $taxPercent;

    protected $wagePercent;

    protected $earnerWagePercent;

    protected $extra;

    protected $base;

    protected $items = [];

    protected $expanded = [];

    public function __construct($tax = 0, $wage = 0, $off = 0, $earnerWage = 0)
    {
        $this->setBase(1)
            ->setTaxPercent($tax)
            ->setWagePercent($wage)
            ->setEarnerWagePercent($earnerWage)
            ->setOffPercent($off);
    }

    public function itemsCost(bool $isNonUsOrder = false)
    {
        $cost = 0;
        foreach ($this->effectiveItems() as $item) {
            $cost += $item->cost($isNonUsOrder) * $item->quantity();
        }

        return $cost;
    }

    public function extra()
    {
        $extra = 0;

        foreach ($this->effectiveItems() as $item) {
            $extra += $item->extra() * $item->quantity();
        }

        return $extra;
    }

    public function tax()
    {
        return Calculator::percent($this->taxPercent, $this->itemsCost());
    }

    public function shipping()
    {
        $amount = 0;

        foreach ($this->effectiveItems() as $item) {
            $amount += $item->shipping();
        }

        return $amount;
    }

    public function totalPrice()
    {
        return $this->itemsCost() + $this->tax();
    }

    public function off()
    {
        return Calculator::percent($this->offPercent, $this->totalPrice());
    }

    public function wage()
    {
        return Calculator::percent($this->wagePercent, $this->totalPrice());
    }

    public function earnerWage()
    {
        return Calculator::percent($this->earnerWagePercent, $this->totalPrice());
    }

    public function profit()
    {
        return $this->totalPrice() - $this->net();
    }

    public function net(bool $isNonUsOrder = false)
    {
        return $this->itemsCost($isNonUsOrder) + $this->tax() + $this->wage() - $this->off();
    }

    public function earnerNetReceived(bool $isNonUsOrder = false)
    {
        return $this->itemsCost($isNonUsOrder) + $this->tax() - $this->off() - $this->earnerWage();
    }

    public function usd()
    {
        $this->setBase(1);

        return $this;
    }

    public function btc(float $rate)
    {
        return $this->setBase($rate);
    }

    public function noWage()
    {
        $this->wagePercent = 0;

        return $this;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function btcAmount($btc)
    {
        return ($this->totalPrice() - $this->off()) * $btc;
    }

    public function effectiveRate($btc)
    {
        return $this->totalPrice() / $this->btcAmount($btc);
    }

    public function effectiveOffPercent($btc)
    {
        return (($this->effectiveRate($btc) * $btc) - 1) * 100;
    }

    public function getOffPercent()
    {
        return $this->offPercent;
    }

    public function setOffPercent($off)
    {
        $this->offPercent = $off;

        return $this;
    }

    public function getTaxPercent()
    {
        return $this->taxPercent;
    }

    public function setTaxPercent($taxPercent)
    {
        $this->taxPercent = $taxPercent;

        return $this;
    }

    public function getWagePercent()
    {
        return $this->wagePercent;
    }

    public function setWagePercent($wage)
    {
        $this->wagePercent = $wage;

        return $this;
    }

    public function getEarnerWagePercent()
    {
        return $this->earnerWagePercent;
    }

    public function setEarnerWagePercent($earnerWage)
    {
        $this->earnerWagePercent = $earnerWage;

        return $this;
    }

    public function addItem(InvoiceItem $item)
    {
        $this->items[] = $item;
        for ($i = 0; $i < $item->quantity(); $i++) {
            $item2 = clone $item;
            $item2->setQuantity(1);
            $this->expanded[] = $item2;
        }

        return $this;
    }

    public function createItem($id, $price, array $meta = [], $quantity = 1, $effective = true)
    {
        return $this->addItem(
            new InvoiceItem($this, $id, $price, $meta, $quantity, $effective)
        );
    }

    /**
     * @return InvoiceItem []
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @return InvoiceItem []
     */
    public function expandedItems()
    {
        return $this->expanded;
    }

    /**
     * @return InvoiceItem []
     */
    public function effectiveItems()
    {
        return array_filter($this->items(), function (InvoiceItem $item) {
            return $item->isEffective();
        });
    }

    public function toArray()
    {
        return [
            'total' => $this->totalPrice(),
            'net' => $this->net(),
            'tax' => $this->tax(),
            'off' => $this->off(),
            'profit' => $this->profit(),
            'wage' => $this->wage(),
        ];
    }

    protected function setBase($base)
    {
        $this->base = $base;

        return $this;
    }
}
