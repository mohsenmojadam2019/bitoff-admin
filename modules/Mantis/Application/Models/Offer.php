<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Application\Support\Hash\MakesHash;
use Bitoff\Mantis\Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use MakesHash;
    use LogsActivity;

    public const BUY = true;

    public const SELL = false;

    public const ACTIVE = true;

    public const INACTIVE = false;

    public const CURRENCY_BTC = 'btc';

    public const CURRENCY_USDT = 'usdt';

    public static $logName = Offer::class;

    protected static $logAttributes = ['active', 'rate', 'min', 'max', 'time', 'terms'];

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'is_buy',
        'currency',
        'rate',
        'min',
        'max',
        'time',
        'terms',
        'active',
        'fee',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->logName = Offer::class;
        $logOptions->logAttributes = ['active', 'rate', 'min', 'max', 'time', 'terms'];
        $logOptions->logOnlyDirty();

        return $logOptions;
    }

    public static function isBuy(): array
    {
        return [self::BUY, self::SELL];
    }

    public function offerer()
    {
        return $this->belongsTo(User::class, 'offerer_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    public function paymentMethodCurrency()
    {
        return $this->belongsTo(Currency::class, 'payment_method_currency_id');
    }

    public static function isActive(): array
    {
        return [self::ACTIVE, self::INACTIVE];
    }

    public static function currencies(): array
    {
        return [self::CURRENCY_USDT, self::CURRENCY_BTC];
    }

    public function getTypeAttribute(): string
    {
        return $this->is_buy ? 'Buy' : 'Sell';
    }

    public function getStatusAttribute(): string
    {
        return $this->active ? 'Active' : 'Inactive';
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'offer_tag');
    }

    protected static function newFactory(): Factory
    {
        return OfferFactory::new();
    }
}
