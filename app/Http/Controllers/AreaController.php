<?php

namespace App\Http\Controllers;

use App\Models\Area;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::select('state_iso', 'state')->groupBy('state_iso', 'state')->get();
        return view('area.index', compact('areas'));
    }

    public function show($state)
    {
        $areas = Area::where('state_iso', $state)->get();
        return view('area.cities', compact('areas', 'state'));
    }

}
