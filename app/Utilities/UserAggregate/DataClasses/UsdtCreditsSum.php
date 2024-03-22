<?php

namespace App\Utilities\UserAggregate\DataClasses;

use App\Models\Credit;
use App\Utilities\UserAggregate\DataContract;
use App\Utilities\UserAggregate\DataContractInterface;

class UsdtCreditsSum extends DataContract implements DataContractInterface
{
    protected string $field = 'usdt_credit_sum';

    public function refresh(): DataContractInterface
    {
        return $this->set(
            $this->aggregate->user->credits()
                ->where('currency', Credit::CURRENCY_USDT)
                ->where('status', Credit::STATUS_CONFIRMATION)
                ->sum('amount')
        );
    }

    public function get(): float
    {
        return (float) parent::get();
    }
}
