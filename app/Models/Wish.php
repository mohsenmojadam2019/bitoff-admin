<?php

namespace App\Models;

use App\Jobs\WishListJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $id
 * @property Order $order
 */
class Wish extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'response' => 'array'
    ];

    protected $dates = ['callback_at'];

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return $this
     */
    public function dispatch(): self
    {
        WishListJob::dispatch($this)->onQueue('wish');

        return $this;
    }

    public function identifier(): string
    {
        return sprintf('%s-%s', $this->order->hash, $this->id);
    }
}
