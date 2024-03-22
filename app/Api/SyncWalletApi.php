<?php

namespace App\Api;

use App\Support\Http\GuzzleHttpRequest;

class SyncWalletApi extends GuzzleHttpRequest
{
    protected $method = 'POST';
    protected $address = '';
    protected $type = '';

    public function getUrl()
    {
        $url = config('bitoff.sync_wallet.url') . '/' . $this->type . '/' . $this->address . '/sync';

        return $url;
    }

    protected function getHeaders()
    {
        return [
            'api-key' => config('bitoff.sync_wallet.token'),
        ];
    }

    public function address($address)
    {
        $this->address = $address;

        return $this;
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }
}
