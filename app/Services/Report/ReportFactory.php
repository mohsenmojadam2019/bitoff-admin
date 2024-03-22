<?php


namespace App\Services\Report;


use App\Http\Controllers\Reports\OrderReportController;
use App\Http\Controllers\Reports\UserReportController;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class ReportFactory
{
    protected $type = [
        'user' => UserReportController::class,
        'order' => OrderReportController::class,
    ];

    public static function create($type)
    {
        return (new static())->getInstance($type);
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function getInstance($type)
    {
        if (isset($this->type[$type])) {
            return app($this->type[$type]);
        }
        return abort(404);
    }
}
