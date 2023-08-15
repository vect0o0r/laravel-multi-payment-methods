<?php

namespace Vector\LaravelMultiPaymentMethods\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MoyasarPaymentController extends Controller
{

    public function paymentView(Request $request): \Illuminate\Contracts\View\View
    {
        dd($request->all());
        return View::make('payment::moyasar.payment_view', ['params' => $request->all()]);
    }

}

