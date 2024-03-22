<?php

namespace App\Services;

use App\Api\Pay\GapApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class AccountGap
{
    const CACHE_KEY = 'account_gap';

    /**
     * @var GapApi
     */
    private $api;

    /**
     * AccountGap constructor.
     *
     * @param GapApi $api
     */
    public function __construct(GapApi $api)
    {
        $this->api = $api;
    }

    /**
     * @return int
     * @throws GuzzleException
     */
    public function getValue(): int
    {
        if ($gap = Cache::get(self::CACHE_KEY)) {
            return $gap;
        }

        $gap = $this->fromApi();

        Cache::put(
            self::CACHE_KEY,
            $gap,
            env('GAP_CACHE_MINUTES', 5)
        );

        return $gap;
    }

    /**
     * @return float|int
     * @throws GuzzleException
     */
    private function fromApi(): float
    {
        return $this->api->send()->gap;
    }
}

