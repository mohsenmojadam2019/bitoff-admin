<?php


namespace App\Api\Pay;


use App\Support\Http\GuzzleHttpRequest;

class UsdtBalanceApi extends GuzzleHttpRequest
{
    protected $method = 'GET';

    public function getUrl()
    {
        return sprintf('%s/tron/balance', config('services.bitpay.url'));
    }

    protected function getHeaders(): array
    {
        return ['api-key' => config('services.bitpay.secret')];
    }
}
