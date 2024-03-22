<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    const CURRENCY_BTC = 'btc';
    const CURRENCY_USDT = 'usdt';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
