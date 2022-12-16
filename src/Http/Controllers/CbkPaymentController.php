<?php

namespace Vector\LaravelMultiPaymentMethods\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CbkPaymentController extends Controller
{

    public function paymentView(Request $request)
    {
        return View::make('payment::cbkPaymentView.blade', ['params' => $request->all()]);
    }

}

