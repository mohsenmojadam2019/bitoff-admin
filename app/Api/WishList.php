<?php

namespace App\Api;

use App\Support\Http\GuzzleHttpRequest;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class WishList extends GuzzleHttpRequest
{
    /**
     * @var $method
     */
    protected $method = 'post';

    /**
     * @return string
     */
    protected function getUrl()
    {
        return env('WISH_URL');
    }

    /**
     * @param array $address
     * @return $this
     */
    public function setAddress(array $address)
    {
        $this->parameters->set('address', $address);
        return $this;
    }

    public function setOrderId($orderId)
    {
        $this->parameters->set('order_id', $orderId);
        return $this;
    }

    /**
     * @param $code
     * @param $qty
     * @param $seller
     * @param $price
     *
     * @return $this
     */
    public function addProduct($code, $qty, $seller, $price)
    {
        $data = [
            'code' => $code,
            'quantity' => $qty,
            'seller_id' => $seller,
            'price' => $this->custom_float_number($price),
        ];

        $current = $this->parameters->get('products', []);
        $this->parameters->set('products', Arr::prepend($current, $data));

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

    private function custom_float_number($number)
    {
        $num = number_format($number, 2);
        return (float) str_replace(',', '', $num);
    }
}
