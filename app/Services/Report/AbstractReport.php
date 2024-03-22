<?php


namespace App\Services\Report;


use Carbon\Carbon;

abstract class AbstractReport
{
    protected $now, $today, $yesterday, $month, $last_month;

    protected $result = [];

    public function __construct()
    {
        $this->now = Carbon::now()->toDateTimeString();
        $this->today = Carbon::now()->toDateString();
        $this->yesterday = Carbon::now()->subDays(1)->toDateString();
        $this->month = Carbon::now()->subMonths(1)->toDateString();
        $this->last_month = Carbon::now()->subMonths(2)->toDateString();
    }

    public static function make()
    {
        return new static;
    }

    abstract public function report();
}
