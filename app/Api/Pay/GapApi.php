<?php

namespace App\Api\Pay;

use App\Support\Http\GuzzleHttpRequest;

class GapApi extends GuzzleHttpRequest
{
    protected $method = 'GET';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return sprintf('%s/bitcoin/gap', config('services.bitpay.url'));
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return ['api-key' => config('services.bitpay.secret')];
    }
}
