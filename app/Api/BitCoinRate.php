<?php


namespace App\Api;



use App\Support\Http\GuzzleHttpRequest;

class BitCoinRate extends GuzzleHttpRequest
{
    protected $method = 'GET';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'http://preev.com/pulse/units:btc+usd/sources:bitstamp+kraken';
    }
}
