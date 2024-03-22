<?php

namespace App\Http\Controllers;

use App\Services\AccountGap;
use App\Services\BitCoinRate;
use App\Services\UsdtBalance;
use App\Services\WalletBalance;
use Illuminate\Support\Facades\Cache;
use Throwable;

class HomeController extends Controller
{
    /**
     * @var WalletBalance
     */
    private $balanceApi;

    /**
     * @var BitCoinRate
     */
    private $rateApi;

    /**
     * @var AccountGap
     */
    private $gapApi;

    private $usdt;

    /**
     * HomeController constructor.
     *
     * @param WalletBalance $balanceApi
     * @param BitCoinRate $rateApi
     * @param AccountGap $gapApi
     */
    public function __construct(WalletBalance $balanceApi, BitCoinRate $rateApi, AccountGap $gapApi, UsdtBalance $usdt)
    {
        $this->balanceApi = $balanceApi;
        $this->rateApi = $rateApi;
        $this->gapApi = $gapApi;
        $this->usdt = $usdt;
    }

    public function index()
    {
        try {
            $rate = $this->rateApi->getAll();
        } catch (Throwable $e) {
            $rate = ['usd' => 0, 'btc' => 0];
        }

        try {
            $balance = $this->balanceApi->getAll();
        } catch (Throwable $e) {
            $balance = ['usd' => 0, 'btc' => 0];
        }

        try {
            $gap = $this->gapApi->getValue();
        } catch (Throwable $e) {
            $gap = -1;
        }
        try {
            $result = $this->usdt->getUsdt();
            $usdt = ($result->usdt) / 1000000;
            $tron = $result->tron / 1000000;
        } catch (Throwable $e) {
            $tron = 0;
            $usdt = 0;
        }

        return view('home', compact('rate', 'balance', 'gap', 'usdt', 'tron'));
    }

    public function basic()
    {
        return view('basic');
    }

    /**
     * Clear wallet api cache, such as (rate, balance, gap)
     */
    public function refresh()
    {
        Cache::forget($this->rateApi::CACHE_KEY);
        Cache::forget($this->balanceApi::CACHE_KEY);
        Cache::forget($this->gapApi::CACHE_KEY);

        return response(['message' => 'Cache has been successfully cleared.']);
    }
}
