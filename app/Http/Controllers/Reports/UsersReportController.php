<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserReportService;
use Illuminate\Support\Facades\DB;

class UsersReportController extends Controller
{
    /**
     * @param \App\Services\UserReportService
     */
    public function index(UserReportService $service)
    {
        return view('reports.users', [
            'ordersAsShoppers' => $service->orderAsShoppers(),
            'ordersAsEarners' => $service->ordersAsEarners(),
            'numberOfVipUser' => $service->numberOfVipUser(),
            'sumCreditVip' => $service->sumCreditVip(),
            'tickets' => $service->ticket(),
            'transaction' => $service->transaction(),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $month = $countReg = [];
        $user = User::query()->selectRaw("count(*) as countReg,month(created_at) as month")
            ->whereRaw('YEAR(created_at) = ? ', [now()->format('Y')])
            ->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month')->get();

        $user->each(function ($reg) use (&$month, &$countReg) {
            $countReg[] = $reg->countReg;
            $month[] = date("F", mktime(0, 0, 0, $reg->month, 1));
        });

        return response()->json([
            'data' => [
                'countReg' => $countReg,
                'month' => $month,
            ],
        ]);
    }
}
