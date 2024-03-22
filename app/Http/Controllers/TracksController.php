<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class TracksController extends Controller
{
    public function index(Request $request)
    {
        $tracks = Track::with('items')->latest();

        if ($origin = $request->get('origin')) {
            $tracks->where(compact('origin'));
        }

        $tracks = $tracks->latest($request->get('sort', 'id'))->paginate(20);

        return view('tracks.index', compact('tracks'));
    }
}
