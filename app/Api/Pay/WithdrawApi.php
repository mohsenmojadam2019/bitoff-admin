<?php


namespace App\Api\Pay;


use App\Support\Http\GuzzleHttpRequest;

class WithdrawApi extends GuzzleHttpRequest
{
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return sprintf('%s/bitcoin/withdraw', config('services.bitpay.url'));
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return ['api-key' => config('services.bitpay.secret')];
    }


    public function setAddress($address)
    {
        $this->setParameter('address', $address);

        return $this;
    }

    /**
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->setParameter('amount', $amount);

        return $this;
    }

    /**
     * @param $fee
     *
     * @return $this
     */
    public function setFee($fee)
    {
        $this->setParameter('fee', $fee);

        return $this;
    }

}
