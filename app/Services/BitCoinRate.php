<?php

namespace App\Services;

use App\Api\Pay\RateApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class BitCoinRate
{
    public const CACHE_KEY = 'bit_rate';

    protected $api;

    /**
     * BitCoinRate constructor.
     */
    public function __construct(RateApi $api)
    {
        $this->api = $api;
    }

    /**
     * @throws GuzzleException
     *
     * @return float|int
     */
    public function asUsd()
    {
        if ($rate = Cache::get(self::CACHE_KEY)) {
            return (float) $rate;
        }

        $rate = $this->fromApi();

        Cache::put(
            self::CACHE_KEY,
            $rate,
            env('RATE_CACHE_MINUTES', 1)
        );

        return $rate;
    }

    /**
     * @throws GuzzleException
     */
    public function getValue(): float
    {
        return number_format(1 / $this->asUsd(), 10);
    }

    /**
     * @throws GuzzleException
     */
    public function getAll(): array
    {
        $usd = $this->asUsd();

        return [
            'usd' => $usd,
            'btc' => number_format(1 / $usd, 10),
        ];
    }

    /**
     * @throws GuzzleException
     *
     * @return float|int
     */
    private function fromApi(string $country = 'usd'): float
    {
        return (float) $this->api->send()->rates->$country;
    }
}
