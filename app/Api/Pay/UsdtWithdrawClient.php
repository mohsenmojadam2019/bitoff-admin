<?php


namespace App\Api\Pay;


use App\Support\Http\GuzzleHttpRequest;

class UsdtWithdrawClient extends GuzzleHttpRequest
{
    /**
     * @var string
     */
    protected $method = 'POST';

    public function getUrl(): string
    {
        return sprintf('%s/usdt/withdraw', config('services.bitpay.url'));
    }

    public function getHeaders()
    {
        return ['api-key' => config('services.bitpay.secret')];
    }

    public function setAmount($amount): UsdtWithdrawClient
    {
        $this->setParameter('amount', $amount);

        return $this;
    }

    public function setAddress(string $address): UsdtWithdrawClient
    {
        $this->setParameter('address', $address);

        return $this;
    }
}
