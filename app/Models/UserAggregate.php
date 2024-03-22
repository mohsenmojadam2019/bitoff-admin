<?php

namespace App\Models;


use App\Utilities\UserAggregate\DataClasses\BtcCreditsSum;
use App\Utilities\UserAggregate\DataClasses\UsdtCreditsSum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property User           $user
 * @property BtcCreditsSum  $btcCreditSum
 * @property UsdtCreditsSum $usdtCreditSum
 */
class UserAggregate extends Model
{
    public const BTC_CREDITS_SUM = 'btcCreditSum';
    public const USDT_CREDITS_SUM = 'usdtCreditSum';

    protected $guarded = [];

    protected $casts = [
        'btc_credit_sum' => 'float',
        'usdt_credit_sum' => 'float',
    ];

    private array $castPropertyClassArray = [
        self::BTC_CREDITS_SUM => BtcCreditsSum::class,
        self::USDT_CREDITS_SUM => UsdtCreditsSum::class,
    ];

    public function __get($key)
    {
        if (in_array($key, array_keys($this->castPropertyClassArray))) {
            return new $this->castPropertyClassArray[$key]($this);
        }

        return parent::__get($key);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
