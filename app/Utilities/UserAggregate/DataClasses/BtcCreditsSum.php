<?php

namespace App\Utilities\UserAggregate\DataClasses;

use App\Models\Credit;
use App\Utilities\UserAggregate\DataContract;
use App\Utilities\UserAggregate\DataContractInterface;

class BtcCreditsSum extends DataContract implements DataContractInterface
{
    protected string $field = 'btc_credit_sum';

    public function refresh(): DataContractInterface
    {
        return $this->set(
            $this->aggregate->user->credits()
                ->where('currency', Credit::CURRENCY_BTC)
                ->where('status', Credit::STATUS_CONFIRMATION)
                ->sum('amount')
        );
    }

    public function get(): float
    {
        return (float) parent::get();
    }
}
