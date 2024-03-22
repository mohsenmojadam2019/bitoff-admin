<?php

namespace App\Http\Controllers\Export;

use App\Exports\Order\CountOrderOfEarner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function countOrderOfEarner()
    {
        return Excel::download(new CountOrderOfEarner(),'earner.xlsx');
    }
}
