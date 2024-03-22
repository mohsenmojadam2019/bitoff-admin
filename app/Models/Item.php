<?php

namespace App\Models;

use App\Services\Invoice\Factory\OrderInvoiceFactory;
use App\Services\Invoice\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\HybridRelations;

/**
 * @property $id
 * @property $status
 * @property $price
 * @property $shipping
 * @property $discount
 * @property $extra
 * @property $wage
 * @property $tax
 * @property $productNet
 * @property $dollarNet
 * @property $bitcoinNet
 * @property Order $order
 */
class Item extends Model
{
    use HybridRelations;
    use HasFactory;

    public const STATUS_INIT = 'init';

    public const STATUS_PURCHASE = 'purchase';

    public const STATUS_SHIP = 'ship';

    public const STATUS_CANCEL = 'cancel';

    public const STATUS_DELIVER = 'deliver';

    protected $table = 'order_items';

    protected $fillable = [
        'amazon_order_id',
        'tracking',
        'status',
        'price',
        'shipping',
        'discount',
        'extra',
        'wage',
        'tax',
    ];

    /** @var array */
    protected $hidden = [
        'id',
        'order_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'amazon_id');
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeExcludeCancelStatus(Builder $query)
    {
        return $query->whereIn('status', [self::STATUS_PURCHASE, self::STATUS_DELIVER])
            ->orWhereNull('status');
    }

    public function getProductNetAttribute()
    {
        return $this->price + $this->shipping - $this->discount;
    }

    public function getDollarNetAttribute()
    {
        return (($this->productNet + $this->tax + $this->extra)
                * ((100 - $this->order->off) / 100)) + $this->wage;
    }

    public function getBitcoinNetAttribute()
    {
        return OrderInvoiceFactory::from($this->order)
            ->setItems($this->order->items->where('id', $this->id))
            ->createInvoice()
            ->btc($this->order->bitcoin_rate)
            ->noWage()
            ->net();
    }

    public function createInvoice(): Invoice
    {
        return OrderInvoiceFactory::from($this->order)
            ->setItems($this->order->items->where('id', $this->id))
            ->createInvoice();
    }

    public function netForEarner()
    {
        $invoice = $this->createInvoice();

        if ($this->order->currency === Order::CURRENCY_BTC) {
            $result = $invoice->btc($this->order->bitcoin_rate)->earnerNetReceived();
        } else {
            $result = $invoice->usd()->earnerNetReceived();
        }

        return $result;
    }

    /**
     * @param string ...$state
     *
     * @return bool
     */
    public function isState(...$state)
    {
        return in_array($this->status, $state);
    }
}
