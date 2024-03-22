<?php


namespace App\Api\Pay;



use App\Support\Http\GuzzleHttpRequest;

class AddressApi extends GuzzleHttpRequest
{
    protected $method = 'POST';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return env('GATEWAY_URL').'/address';
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return ['api-key' => env('GATEWAY_TOKEN')];
    }

}
