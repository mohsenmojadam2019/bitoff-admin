<?php


namespace App\Services;


use App\Api\Pay\UsdtBalanceApi;
use Illuminate\Support\Facades\Cache;

class UsdtBalance
{
    private $usdt;
    const USDTWallet = 'usdt_wallet';

    public function __construct(UsdtBalanceApi $usdt)
    {
        $this->usdt = $usdt;
    }

    public function getUsdt()
    {
        $result = Cache::get(self::USDTWallet);
        if($result){
            return  $result;
        }

        $result =  $this->usdt->send();

        Cache::put(self::USDTWallet,$result,env('BALANCE_CACHE_MINUTES', 5));

        return $result;
    }
}
