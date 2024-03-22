<?php


namespace App\Api\Pay;


use App\Support\Http\GuzzleHttpRequest;

class BalanceApi extends GuzzleHttpRequest
{
    protected $method = 'GET';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return sprintf(
            '%s/bitcoin/balance',
            env('GATEWAY_URL')
        );
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return ['api-key' => env('GATEWAY_TOKEN')];
    }
}
