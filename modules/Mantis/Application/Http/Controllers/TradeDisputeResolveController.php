<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Models\Trade;
use Illuminate\Routing\Controller;

class TradeDisputeResolveController extends Controller
{
    public function __invoke(Trade $trade)
    {
        $trade->update(['status' => Trade::STATUS_PAID]);
        
        return response()->json();
    }
}
