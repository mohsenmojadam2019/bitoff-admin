<?php


namespace App\Services\Report\Order;


use App\Models\Order;
use App\Services\Report\AbstractReport;
use Illuminate\Http\Request;

class OrderCountReport extends AbstractReport
{
    public function report(Request $request = null)
    {
        $base_query = Order::where(function ($query) use ($request) {
            if ($request->status) {
                $query->where('status', $request->status);
            }

            if (isset($request->prime)) {
                $query->where('prime', $request->prime);
            }

            if ($request->level) {
                $query->where('level', $request->level);
            }
        });

        $scope_query = clone $base_query;
        $this->result['today'] = $scope_query->whereBetween('created_at', [$this->today, $this->now])->count();

        $scope_query = clone $base_query;
        $this->result['yesterday'] = $scope_query->whereBetween('created_at', [$this->yesterday, $this->today])->count();

        $scope_query = clone $base_query;
        $this->result['month'] = $scope_query->whereBetween('created_at', [$this->month, $this->now])->count();

        $scope_query = clone $base_query;
        $this->result['last_month'] = $scope_query->whereBetween('created_at', [$this->last_month, $this->month])->count();

        $this->result['all'] = $base_query->count();

        return $this->result;
    }
}
