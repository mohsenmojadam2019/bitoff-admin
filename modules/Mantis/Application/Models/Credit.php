<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Database\Factories\CreditFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    public const CURRENCY_BTC = 'btc';
    public const CURRENCY_USDT = 'usdt';

    public const TYPE_SELL_TRADE = 'sell_trade';
    public const TYPE_BUY_TRADE = 'buy_trade';
    public const TYPE_ADMIN = 'admin';
    public const TYPE_CANCEL_TRADE = 'cancel_trade';

    public const STATUS_CONFIRMATION = 'confirmed';
    public const STATUS_UNCONFIRMATION = 'unconfirmed';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'extra',
        'currency',
    ];

    public function creditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): Factory
    {
        return CreditFactory::new();
    }
}
