<?php


namespace App\Http\Controllers;


use App\Services\Report\Order\OrderCountReport;
use App\Services\Report\Ticket\TicketCountReport;
use App\Services\Report\Transaction\TransactionAmountReport;
use App\Services\Report\Transaction\TransactionCountReport;
use App\Services\Report\User\UserCountReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show($category = 'users')
    {
        return view('report.filter.' . $category, compact('category'));
    }

    public function filter($category, Request $request)
    {
        switch ($category) {
            case 'users':
                $reports = [
                    'count' => UserCountReport::make()->report($request)
                ];
                break;
            case 'transactions':
                $reports = [
                    'count' => TransactionCountReport::make()->report($request),
                    'amount' => TransactionAmountReport::make()->report($request)
                ];
                break;
            case 'orders':
                $reports = [
                    'count' => OrderCountReport::make()->report($request)
                ];
                break;
            case 'tickets':
                $reports = [
                    'count' => TicketCountReport::make()->report($request),
                ];
                break;
            default:
                return 'Invalid report type';
                break;
        }

        return view('report.result', compact('reports'));
    }
}
