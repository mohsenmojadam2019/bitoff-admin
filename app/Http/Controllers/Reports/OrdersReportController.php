<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\OrderReportService;

class OrdersReportController extends Controller
{
    public function index(OrderReportService $service)
    {
        return view('reports.orders', [
            'numberOfOrderLastThreeMonth' => $service->numberOfOrderLastThreeMonth(),
            'numberOfStatusLastMonth' => $service->numberOfOrderLastMonth(),
            'numberOfStatusLastWeek' => $service->numberOfOrderLastWeek(),
            'numberOfStatusToDay' => $service->numberOfOrderToDay(),
            'sumTotalPriceOrder' => $service->sumTotalPriceOrder(),
            'numberOfOrderItemDelivered' => $service->numberOfOrderItemDelivered(),
            'totalWage' => $service->totalWage(),
            'totalPriceBeforeOff' => $service->totalPriceBeforeOff(),
            'totalPriceNextOff' => $service->totalPriceNextOff(),
            'escrow' => $service->escrow(),
            'wage' => $service->wage(),
            'numberOfOrders' => $service->numberOfOrders(),
        ]);
    }
}
