<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardService $service)
    {
        return view('dashboard', [
            'ordersBtcAccepted' => $service->ordersBtcAccepted($request),
            'ordersUsdtAccepted' => $service->ordersUsdtAccepted($request),
            'ordersWithBtcCurrency' => $service->ordersWithBtcCurrency($request),
            'ordersWithUsdtCurrency' => $service->ordersWithUsdtCurrency($request),
            'ordersBtcWaitForEarner' => $service->ordersBtcWaitForEarner($request),
            'ordersUsdtWaitForEarner' => $service->ordersUsdtWaitForEarner($request),
            'ordersBtcByEarnerSupported' => $service->ordersBtcByEarnerSupported($request),
            'ordersUsdtByEarnerSupported' => $service->ordersUsdtByEarnerSupported($request),
            'ordersBtcByShopperSupported' => $service->ordersBtcByShopperSupported($request),
            'ordersUsdtByShopperSupported' => $service->ordersUsdtByShopperSupported($request),
            'ordersBtcSupportedAndResolved' => $service->ordersBtcSupportedAndResolved($request),
            'ordersUsdtSupportedAndResolved' => $service->ordersUsdtSupportedAndResolved($request),
            'ordersBtcCanceled' => $service->ordersBtcCanceled($request),
            'ordersUsdtCanceled' => $service->ordersUsdtCanceled($request),
            'ordersBtcIssueFounded' => $service->ordersBtcIssueFounded($request),
            'ordersUsdtIssueFounded' => $service->ordersUsdtIssueFounded($request),
        ]);
    }
}
