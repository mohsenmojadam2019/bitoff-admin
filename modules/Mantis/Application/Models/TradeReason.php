<?php

namespace Bitoff\Mantis\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeReason extends Model
{
    protected $guarded = [];

    /**
     * Relation with Trade
     *
     * @return BelongsTo
     */
    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class, 'trade_id');
    }

    /**
     * Relation with User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
