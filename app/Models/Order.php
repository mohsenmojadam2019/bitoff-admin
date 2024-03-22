<?php

namespace App\Models;

use App\Services\BitCoinRate;
use App\Services\Invoice\Factory\OrderInvoiceFactory;
use App\Services\Invoice\Invoice;
use App\Support\Hash\MakesHash;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Traits\HaveFeedback;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MongoDB\Laravel\Eloquent\HybridRelations;

/**
 * @property                  $id
 * @property                  $hash
 * @property                  $status
 * @property                  $off
 * @property                  $bitcoin_rate
 * @property                  $edit_times
 * @property                  $address
 * @property                  $currency
 * @property                  $net
 * @property                  $shopper_wage_percent
 * @property                  $shopper_wage_amount
 * @property Collection $editions
 * @property User $shopper
 * @property User $earner
 * @property                  $shopper_id
 * @property                  $earner_id
 * @property                  $wl_id
 * @property                  $wl_amazon_id
 * @property                  $wl_link
 * @property                  $wl_tries
 * @property                  $wl_last_try
 * @property Carbon $reserved_at
 * @property                  $reserve_id
 * @property Reservation|null $reservation
 * @property Collection $items
 * @property Collection $feedbacks
 * @property Address $addressBook
 *
 * @method static Builder status($status)
 */
class Order extends Model
{
    use MakesHash;
    use HybridRelations;
    use HasFactory;
    use HaveFeedback;

    public const MAX_WAGE = 5.69;

    public const MAX_OTHER_WAGE = 5.78;

    public const CANADA_SOURCE = 'canada';

    public const UNITED_KINGDOM_SOURCE = 'united kingdom';

    public const BITOFF_SOURCE = 'bitoff';

    public const CURRENCY_BTC = 'btc';

    public const CURRENCY_USDT = 'usdt';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CREDIT_PENDING = 'credit_pending';

    public const STATUS_RESERVE = 'reserve';

    public const STATUS_PURCHASE = 'purchase';

    public const STATUS_PARTIAL_SHIP = 'partial_ship';

    public const STATUS_SHIP = 'ship';

    public const STATUS_PARTIAL_DELIVER = 'partial_deliver';

    public const STATUS_WISH_PENDING = 'wish_pending';

    public const STATUS_DELIVER = 'deliver';

    public const STATUS_WISH_CALLBACK = 'wish_callback';

    public const STATUS_CANCEL = 'cancel';

    public const STATUS_COMPLETE = 'complete';

    public const STATUS_WISH_FAIL = 'wish_fail';

    public const STATUS_ISSUE_FOUNDED = 'issue_founded';

    public const STATUS = [
        'P' => 'pending',
        'NO_CREDIT' => 'credit_pending',
        'R' => 'reserve',
        'PC' => 'purchase',
        'C' => 'cancel',
        'PS' => 'partial_ship',
        'S' => 'ship',
        'D' => 'deliver',
        'PD' => 'partial_deliver',
        'CM' => 'complete',
        'WL_PENDING' => 'wish_pending',
        'WL_CALLBACK_PENDING' => 'wish_callback',
        'WL_FAIL' => 'wish_fail',
        'ISSUE' => 'issue_founded',
    ];

    protected $invoice;

    protected $casts = [
        'address' => 'array',
        'reserved_at' => 'datetime',
        'wl_last_try' => 'datetime',
        'meta' => 'array',
    ];

    protected $fillable = [
        'status',
        'wl_link',
        'wl_id',
        'wl_amazon_id',
        'reserve_id',
        'reserved_at',
        'earner_id',
        'bitcoin_rate',
        'support',
    ];

    public function getInvoice(): Invoice
    {
        if ($this->invoice instanceof Invoice) {
            return $this->invoice;
        }
        $this->invoice = OrderInvoiceFactory::from($this)->createInvoice();

        return $this->invoice;
    }

    public function getNetAttribute()
    {
        return $this->getInvoice()
            ->btc($this->bitcoin_rate ?: app(BitCoinRate::class)->getValue())
            ->net();
    }

    public function earner()
    {
        return $this->belongsTo(User::class, 'earner_id', 'id');
    }

    public function shopper()
    {
        return $this->belongsTo(User::class, 'shopper_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'order_id')->latest();
    }

    public function activities()
    {
        return $this->hasMany(Log::class, 'order_id')->where('type', '!=', 'status');
    }

    public function credits()
    {
        return $this->morphMany(Credit::class, 'creditable');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'order_id')->latest();
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reserve_id', 'id');
    }

    /**
     * @return Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        $method = is_array($status) ? 'whereIn' : 'where';

        return call_user_func([$query, $method], 'status', $status);
    }

    /**
     * @param bool $commit
     * @param bool $logging
     *
     * @return $this
     */
    public function toState($state, $commit = true, $logging = true)
    {
        $this->status = $state;

        if ($commit) {
            $this->save();
        }

        if ($logging) {
            $this->storeLog([
                'type' => 'status',
                'changes' => [
                    'status' => $state,
                ],
            ]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function storeLog(array $log)
    {
        $this->logs()->create($log);

        return $this;
    }

    /**
     * @return bool
     */
    public function needsWish()
    {
        return in_array($this->status, [self::STATUS['WL_PENDING'], self::STATUS['WL_CALLBACK_PENDING']]);
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

    public function removeEarner($commit = true)
    {
        $this->reserve_id = null;
        $this->reserved_at = null;
        $this->earner_id = null;
        $this->bitcoin_rate = null;
        $this->support = false;

        if ($commit) {
            $this->save();
        }

        return $this;
    }

    public function freshItems()
    {
        $this->items()->update(['status' => Item::STATUS_INIT, 'tracking' => null, 'confirmation' => null]);

        return $this;
    }

    public function isNative()
    {
        return $this->source === 'bitoff';
    }

    public function tracks()
    {
        return $this->hasManyThrough(Track::class, OrderItem::class, 'order_id', 'order_item_id');
    }

    public function totalBtcAmount()
    {
        return $this->items()->sum('price') * $this->bitcoin_rate;
    }

    public function totalUsdtAmount()
    {
        return $this->items()->sum('price');
    }
}
