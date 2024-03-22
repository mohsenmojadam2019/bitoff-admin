<?php


namespace App\Api;


use App\Support\Http\GuzzleHttpRequest;
use GuzzleHttp\Exception\GuzzleException;

class TransactionApi extends GuzzleHttpRequest
{

    protected $method = 'POST';

    protected function getUrl()
    {
        return env('API_URL') . "/a/wallets/tx/{$this->parameters->get('id')}/withdraw";
    }

    protected function headers()
    {
        return [
            'token' => env('WALLET_API_ACCESS_TOKEN')
        ];
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->parameters->set('id', $id);
        return $this;
    }

    /**
     * @param $desc
     * @return $this
     */
    public function setDescription($desc)
    {
        $this->parameters->set('desc', $desc);
        return $this;
    }

    /**
     * @param GuzzleException $exception
     * @throws GuzzleException
     */
    protected function handler(GuzzleException $exception)
    {
        throw $exception;
    }

}
