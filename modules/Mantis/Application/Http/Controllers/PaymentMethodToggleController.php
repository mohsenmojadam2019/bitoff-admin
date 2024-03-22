<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Models\PaymentMethod;
use Illuminate\Routing\Controller;

class PaymentMethodToggleController extends Controller
{
    public function __invoke(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['active' => $paymentMethod->active ? PaymentMethod::INACTIVE : PaymentMethod::ACTIVE]);

        return back();
    }
}
