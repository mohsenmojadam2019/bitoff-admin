<?php

namespace Bitoff\Mantis\Application\Models;

use Bitoff\Mantis\Database\Factories\CurrencyFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends Model
{
    use HasFactory;

    public const UNITED_STATE = 'usd';

    public const CANADA = 'cad';

    public const UNITED_KINGDOM = 'gbp';

    public $timestamps = false;

    public static function currencies(): array
    {
        return [
            self::UNITED_STATE,
            self::UNITED_KINGDOM,
            self::CANADA,
        ];
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }

    protected static function newFactory(): Factory
    {
        return CurrencyFactory::new();
    }
}
