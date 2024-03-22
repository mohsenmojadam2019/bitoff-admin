<?php

namespace App\Models;

use App\Models\Traits\Relations\AggregateUserData;
use App\Utilities\Shops;
use Bitoff\Feedback\Application\Models\Feedback;
use Bitoff\Feedback\Traits\UserHaveFeedbacks;
use Bitoff\Feedback\Traits\UserHaveLevels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin AggregateUserData
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles, HasFactory;
    use AggregateUserData;
    use Shops;
    use UserHaveFeedbacks;
    use UserHaveLevels;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'active',
        'blocked',
        'admin',
        'fast_release',
        'send_wallet_notif',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany
     */
    public function credits()
    {
        return $this->hasMany(Credit::class)->latest();
    }

    public function currency_usdt(): HasMany
    {
        return $this->hasMany(Credit::class)
            ->where('currency', Credit::CURRENCY_USDT)
            ->latest();
    }

    public function currency_btc(): HasMany
    {
        return $this->hasMany(Credit::class)
            ->where('currency', Credit::CURRENCY_BTC)
            ->latest();
    }

    public function shops()
    {
        return $this->hasMany(Order::class, 'shopper_id', 'id')
            ->latest();
    }

    public function earns()
    {
        return $this->hasMany(Order::class, 'earner_id', 'id')
            ->latest();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

//    public function scores()
//    {
//        return $this->hasMany(Score::class, 'to_user_id', 'id')->latest();
//    }
//
//    public function givenScores()
//    {
//        return $this->hasMany(Score::class, 'from_user_id', 'id')->latest();
//    }

    public function receivedFeedbacks(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'feedbackable', 'feedbackable_type', 'to_user_id', 'id')->latest();
    }

    public function givenFeedbacks(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'feedbackable', 'feedbackable_type', 'from_user_id', 'id')->latest();
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->latest();
    }

//    public function getEarnScoreAttribute()
//    {
//        if (!$this->relationLoaded('scores')) {
//            $this->load('scores');
//        }
//
//        return $this->scores->where('role', 'earner')->sum('score');
////        return 60;
//    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getIdentifierAttribute()
    {
        return $this->username ? '@' . $this->username : $this->email;
    }

    /**
     * @return float
     */
    public function getConfirmedCreditAttribute()
    {
        return $this->credits->where('status', Credit::STATUS['C'])->sum('amount');
    }

    /**
     * @return float
     */
    public function getUnconfirmedCreditAttribute()
    {
        return $this->credits->where('status', Credit::STATUS['U'])->sum('amount');
    }

    /**
     * @param $currency
     *
     * @return float|int
     */
    public function getCreditSum($currency)
    {
        return $currency === Credit::CURRENCY_USDT ?
            $this->aggregate->usdtCreditSum->get() : $this->aggregate->btcCreditSum->get();
    }

    public function hasEnoughCreditFor(Order $order): bool
    {
        return $this->getCreditSum($order->currency) >= $order->net;
    }

    public function verification()
    {
        return $this->hasOne(Verification::class, 'user_id')->latest();
    }

    public function block(): bool
    {
        return $this->update([
            'blocked' => true
        ]);
    }

    public function unblock(): bool
    {
        return $this->update([
            'blocked' => false
        ]);
    }
}
