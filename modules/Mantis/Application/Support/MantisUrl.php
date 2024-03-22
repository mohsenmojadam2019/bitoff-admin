<?php

namespace Bitoff\Mantis\Application\Support;

class MantisUrl
{
    public static function base()
    {
        return config('services.frontend_address');
    }

    public static function trade($offerId, $tradeId)
    {
        return sprintf('%s/p/trade/%s/%s', static::base(), $tradeId, $offerId);
    }
}
