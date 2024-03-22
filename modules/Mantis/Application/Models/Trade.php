<?php

namespace Bitoff\Mantis\Application\Models;

use App\Support\Hash\MakesHash;
use Bitoff\Mantis\Database\Factories\TradeFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Trade extends Model
{
    use HasFactory;
    use MakesHash;
    use SoftDeletes;
    use LogsActivity;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_DISPUTE = 'dispute';

    public const STATUS_PAID = 'paid';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELED = 'canceled';

    public const STATUS_RELEASED = 'released';

    public const STATUS_EXPIRED = 'expired';

    protected static $logAttributes = ['status', 'amount', 'net_amount', 'fee'];

    protected static $logName = Trade::class;

    protected static $logOnlyDirty = true;

    protected $fillable = [
        'amount',
        'status',
    ];

    public function getOfferDataAttribute($value)
    {
        return json_decode($value);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function trader()
    {
        return $this->belongsTo(User::class, 'trader_id');
    }

    public function credits()
    {
        return $this->morphMany(Credit::class, 'creditable');
    }

    public function feedbacks()
    {
        return $this->morphMany(\Bitoff\Feedback\Application\Models\Feedback::class,'feedbackable');
    }

    public static function status(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_CANCELED,
            self::STATUS_COMPLETED,
            self::STATUS_DISPUTE,
            self::STATUS_EXPIRED,
            self::STATUS_RELEASED,
            self::STATUS_PAID,
        ];
    }

    public function isStatus(...$state): bool
    {
        return in_array($this->status, $state);
    }

    public function remainingTime()
    {
        $offerTime = $this->offer_data->time;

        if ($this->isStatus(Trade::STATUS_ACTIVE)) {
            $expireDate = $this->getLatestReopenDate() ?? $this->created_at;

            return $expireDate->addMinutes($offerTime)->toDateTimeString();
        }

        return $this->created_at->subMinutes($offerTime)->toDateTimeString();
    }

    /**
     * Relation with TradeReason.
     */
    public function tradeReason(): HasMany
    {
        return $this->hasMany(TradeReason::class, 'trade_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->logName = Trade::class;
        $logOptions->logAttributes = ['status', 'amount', 'net_amount', 'fee'];
        $logOptions->logOnlyDirty();

        return $logOptions;
    }

    protected static function newFactory(): Factory
    {
        return TradeFactory::new();
    }

    private function getLatestReopenDate(): ?Carbon
    {
        $activity = Activity::where('subject_type', Trade::class)
            ->where('subject_id', $this->id)
            ->where('description', 'updated')
            ->where(function ($query) {
                $query->whereJsonContains('properties->old', ['status' => Trade::STATUS_CANCELED])
                    ->orWhereJsonContains('properties->old', ['status' => Trade::STATUS_EXPIRED]);
            })
            ->whereJsonContains('properties->attributes', ['status' => Trade::STATUS_ACTIVE])
            ->orderBy('created_at', 'desc')
            ->first();

        return $activity ? $activity->created_at : null;
    }
}
