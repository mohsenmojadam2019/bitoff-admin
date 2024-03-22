<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Credit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const CURRENCY_BTC = 'btc';
    public const CURRENCY_USDT = 'usdt';

    public const TYPE_SHOP = 'shop';
    public const TYPE_EARN = 'earn';
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAW = 'withdraw';
    public const TYPE_WITHDRAW_BLOCK = 'withdraw_block';
    public const TYPE_FAST_RELEASE = 'fast_release';
    public const TYPE_ADMIN = 'admin';
    public const TYPE_CANCEL = 'cancel';

    public const STATUS_CONFIRMATION = 'confirmed';
    public const STATUS_UNCONFIRMATION = 'unconfirmed';

    const STATUS = [
        'U' => 'unconfirmed',
        'C' => 'confirmed'
    ];

    protected $casts = [
        'extra' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo
     */
    public function creditable()
    {
        return $this->morphTo();
    }

}
