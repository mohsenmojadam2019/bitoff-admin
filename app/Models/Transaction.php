<?php

namespace App\Models;

use App\Support\Currency;
use App\Support\Hash\MakesHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Transaction extends Model
{
    use MakesHash;

    const STATUS = [
        'pending',
        'success',
        'failed',
        'admin_pending',
        'credit_pending',
        'admin_confirm'
    ];

    const TYPES = [
        'deposit',
        'withdraw',
        'order_reserve',
        'order_cancel',
        'order_deliver',
        'gap'
    ];

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphOne
     */
    public function credit()
    {
        return $this->morphOne(Credit::class, 'creditable');
    }

    public function isPending()
    {
        return in_array($this->status, ['admin_pending', 'credit_pending']);
    }

    public function getCreditValue()
    {
        if ($this->fee) {
            $fee = Currency::satoshiToBtc($this->fee);
        }

        if ($this->type == 'withdraw') {
            return (float)($this->amount + $fee ?: 0);
        }

        return (float)$this->amount;
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function ip()
    {
        return $this->belongsTo(\App\Models\UserIp::class, 'user_ip');
    }
}
