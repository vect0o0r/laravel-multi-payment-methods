<?php

use Illuminate\Support\Facades\Route;
use Vector\LaravelMultiPaymentMethods\Http\Controllers\CbkPaymentController;

Route::get('/cbk/payment-view', [CbkPaymentController::class, 'paymentView'])->name('cbk.payment-view');

