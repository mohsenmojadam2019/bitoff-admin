<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserReportService
{
    /**
     * @return int
     */
    public function numberOfVipUser(): int
    {
        return DB::table('users')
            ->where('fast_release', 1)
            ->count();
    }

    /**
     * @return float
     */
    public function sumCreditVip(): float
    {
        $credit = DB::table('credits')
            ->where('type', 'fast_release')
            ->where('currency', 'btc')
            ->sum('amount');

        return $credit * -1;
    }

    /**
     * @return int
     */
    public function ticket()
    {
        return DB::table('tickets')
            ->selectRaw("count(*) as ticketCount,status")
            ->groupBy('status')
            ->get();
    }

    /**
     * @return Illuminate\Support\Collection
     */
    public function transaction()
    {
        $transaction = DB::table('transactions')
            ->selectRaw('
                ifnull(sum((amount - (fee * 0.00000001))/ifnull(rate,1)),0) as dollar , ifnull(sum(amount- (fee * 0.00000001)),0) as currency
            ')->where('currency', request()->query('currency', 'btc'));

        if (request()->query('from_date') != "") {
            $transaction->whereRaw('date(created_at) >= ?', [request()->query('from_date')]);
        }

        if (request()->query('to_date') != "") {
            $transaction->whereRaw('date(created_at) <= ?', [request()->query('to_date')]);
        }

        if (request()->query('status') != "") {
            $transaction->whereRaw('status = ?', [request()->query('status')]);
        }

        if (request()->query('type') != "") {
            $transaction->whereRaw('type = ?', [request()->query('type')]);
        }

        return $transaction->first();
    }

    /**
     * @return Illuminate\Support\Collection
     */
    public function orderAsShoppers()
    {
        return DB::table('users')->leftJoin(DB::raw("
            (
                select count(*) as shopCount,shopper_id from orders
                group by shopper_id
            ) as shopper
        "), 'shopper.shopper_id', '=', 'users.id')
            ->orderBy('shopCount', 'desc')
            ->limit(10)
            ->get();
    }

    public function ordersAsEarners()
    {
        return DB::table('users')->leftJoin(DB::raw("
            (
                select count(*) as earnCount,earner_id from orders
                group by earner_id
            ) as earner
        "), 'earner.earner_id', '=', 'users.id')
            ->orderBy('earnCount', 'desc')
            ->limit(10)
            ->get();
    }
}
