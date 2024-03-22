<?php

namespace Bitoff\Mantis\Application\Services\Calculate;

interface CalculateServiceInterface
{
    public function calculate(float $originAmount, array $offerData): array;

    public function appliedCurrencyPrice(float $offerRate): float;
}
