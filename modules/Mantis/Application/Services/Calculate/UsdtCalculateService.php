<?php

namespace Bitoff\Mantis\Application\Services\Calculate;

class UsdtCalculateService implements CalculateServiceInterface
{
    public function calculate(float $originAmount, array $offerData): array
    {
        $offerRate = $offerData['rate'];
        $offerFee = $offerData['fee'];

        $usdtSaveWithoutFee = $originAmount * ($offerRate / 100);
        $usdtAmountAfterRate = $originAmount - $usdtSaveWithoutFee;

        $usdtFee = $originAmount * ($offerFee / 100);
        $usdtSave = $usdtSaveWithoutFee - $usdtFee;

        return [
            'bitcoin_rate' => null,
            'net_amount' => $usdtAmountAfterRate,
            'net_amount_in_usd' => $usdtAmountAfterRate,
            'btc_save' => null,
            'usd_save' => $usdtSave,
            'fee' => $usdtFee,
            'subtractedAmount' => $usdtAmountAfterRate + $usdtFee,
        ];
    }

    public function appliedCurrencyPrice(float $offerRate): float
    {
        /*
         * calculates usdt price based on offer rate(off)
         * usdt price is 1 here
         */
        return 100 / (100 - $offerRate);
    }
}
