<?php

namespace App\Services;

use App\Api\Pay\BalanceApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class WalletBalance
{
    protected $api;
    const CACHE_KEY = 'wallet_balance';
    const WITHDRAW_WALLET = 'w';
    const ONE_BITCOIN_IN_SATOSHI = 100000000;

    /**
     * @var BitCoinRate
     */
    private $rate;

    /**
     * WalletBalance constructor.
     *
     * @param BalanceApi $api
     * @param BitCoinRate $rate
     */
    public function __construct(BalanceApi $api, BitCoinRate $rate)
    {
        $this->api = $api;
        $this->rate = $rate;
    }

    /**
     * @return float|int
     * @throws GuzzleException
     */
    public function asUsd()
    {
        $balance = number_format($this->getValue() / $this->rate->getValue(), 2);

        return $balance;
    }

    /**
     * @return float
     * @throws GuzzleException
     */
    public function getValue(): float
    {
        if ($balance = Cache::get(self::CACHE_KEY)) {
            return (float)$balance;
        }

        $balance = number_format($this->fromApi() / self::ONE_BITCOIN_IN_SATOSHI, 10);

        Cache::put(
            self::CACHE_KEY,
            $balance,
            env('BALANCE_CACHE_MINUTES', 5)
        );

        return (float)$balance;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAll(): array
    {
        return [
            'usd' => $this->asUsd(),
            'btc' => $this->getValue()
        ];
    }

    /**
     * @return float|int
     * @throws GuzzleException
     */
    private function fromApi(): float
    {
        return $this->api->send()->balance;
    }
}

