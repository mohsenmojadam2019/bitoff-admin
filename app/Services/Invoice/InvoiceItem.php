<?php


namespace App\Services\Invoice;


use App\Support\Calculator;

class InvoiceItem
{
    private $invoice;
    private $price;
    private $meta;
    private $id;
    private $quantity;
    private $extra = 0;
    private $effective;

    public function __construct(Invoice $invoice, $id, $price, array $meta = [], $quantity = 1, bool $effective = true)
    {
        $this->invoice = $invoice;
        $this->price = $price;
        $this->id = $id;
        $this->meta = $meta;
        $this->quantity = $quantity;
        $this->effective = $effective;

        if (array_key_exists('extra', $meta)) {
            $this->extra = $meta['extra'];
        }
    }

    public function price(bool $isNonUsOrder = false)
    {
        if($isNonUsOrder){
            $this->price = $this->meta()['origin_price'];
        }
        return $this->asBase($this->price);
    }

    public function cost(bool $isNonUsOrder = false)
    {
        return $this->price($isNonUsOrder) + $this->shipping() + $this->extra();
    }

    public function tax()
    {
        return Calculator::percent($this->invoice->getTaxPercent(), $this->cost());
    }

    public function wage()
    {
        return Calculator::percent(
            $this->invoice->getWagePercent(),
            $this->cost() + $this->tax()
        );
    }

    public function extra()
    {
        return $this->asBase($this->extra);
    }

    public function shipping($default = 0)
    {
        return $this->asBase($this->fromMeta('shipping', $default));
    }

    public function isEffective()
    {
        return $this->effective;
    }

    public function meta()
    {
        return $this->meta;
    }

    public function product($default = null)
    {
        return $this->fromMeta('product', $default);
    }

    public function fromMeta($key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }

    public function quantity()
    {
        return $this->quantity;
    }

    public function setQuantity(int $qty)
    {
        $this->quantity = $qty;

        return $this->quantity;
    }

    protected function asBase($value)
    {
        return $value * $this->invoice->getBase();
    }
}
