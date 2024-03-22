<?php


namespace App\Services\Report\User;


use App\Models\User;
use App\Services\Report\AbstractReport;
use Illuminate\Http\Request;

class UserCountReport extends AbstractReport
{
    public function report(Request $request = null)
    {
        $base_query = User::where(function ($query) use ($request) {
            if (isset($request->admin)) {
                $query->where('admin', $request->admin);
            }

            if (isset($request->active)) {
                $query->where('active', $request->active);
            }

            if ($request->provider) {
                $query->where('provider', $request->provider);
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
