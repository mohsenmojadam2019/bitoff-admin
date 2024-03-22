<?php

namespace Bitoff\Mantis\Application\Services\Calculate;

use Bitoff\Mantis\Application\Models\Credit;
use InvalidArgumentException;

class CalculateServiceFactory
{
    public $currency;

    public function currency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function createCalculator(): CalculateServiceInterface
    {
        switch ($this->currency) {
            case Credit::CURRENCY_BTC:
                return resolve(BtcCalculateService::class);

                break;
            case Credit::CURRENCY_USDT:
                return resolve(UsdtCalculateService::class);

                break;

            default:
                throw new InvalidArgumentException("Invalid {$this->currency} type ");

                break;
        }
    }
}
