<?php

namespace Bitoff\Mantis\Application\Services\Calculate;

use App\Services\BitCoinRate;

class BtcCalculateService implements CalculateServiceInterface
{
    public $btcPriceAsUsd;
    public $btcPrice;

    public function __construct(BitCoinRate $btcPriceAsUsd)
    {
        $this->btcPrice = $btcPriceAsUsd->getValue();
        $this->btcPriceAsUsd = $btcPriceAsUsd->asUsd();
    }

    public function calculate(float $originAmount, array $offerData): array
    {
        $offerRate = $offerData['rate'];
        $offerFee = $offerData['fee'];

        $usdtSaveWithoutFee = $originAmount * ($offerRate / 100);
        $usdtAmountAfterRate = $originAmount - $usdtSaveWithoutFee;

        $btcAmountAfterRate = $usdtAmountAfterRate / $this->btcPriceAsUsd;
        $usdtFee = $originAmount * ($offerFee / 100);
        $btcFee = $usdtFee / $this->btcPriceAsUsd;
        $usdtSave = $usdtSaveWithoutFee - $usdtFee;
        $btcSave = $usdtSave / $this->btcPriceAsUsd;

        return [
            'bitcoin_rate' => $this->btcPrice,
            'net_amount' => $btcAmountAfterRate,
            'net_amount_in_usd' => $usdtAmountAfterRate,
            'btc_save' => $btcSave,
            'usd_save' => $usdtSave,
            'fee' => $btcFee,
            'subtractedAmount' => $btcAmountAfterRate + $btcFee,
        ];
    }

    public function appliedCurrencyPrice(float $offerRate): float
    {
        // calculates bitcoin price based on offer rate(off)
        return ($this->btcPriceAsUsd / (100 - $offerRate)) * 100;
    }
}
