<?php


namespace App\Support;


class Currency
{
    const BTX_PER_SATOSHI = 100000000;

    public static function toSatoshi($x)
    {
        return (int) ($x * self::BTX_PER_SATOSHI);
    }

    public static function satoshiToBtc($btc)
    {
        return $btc / self::BTX_PER_SATOSHI;
    }

}
