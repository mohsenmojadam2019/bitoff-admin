<?php


namespace App\Api\Pay;


use App\Support\Http\GuzzleHttpRequest;

class RateApi extends GuzzleHttpRequest
{
    protected $method = 'GET';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return sprintf('%s/bitcoin/rates', env('GATEWAY_URL'));
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return ['api-key' => env('GATEWAY_TOKEN')];
    }
}
