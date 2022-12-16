<?php

namespace Vector\LaravelMultiPaymentMethods\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CbkPaymentController extends Controller
{

    public function paymentView(Request $request): \Illuminate\Contracts\View\View
    {
        return View::make('payment::cbk.payment_view', ['params' => $request->all()]);
    }

}

