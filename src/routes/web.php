<?php

use Illuminate\Support\Facades\Route;
use Vector\LaravelMultiPaymentMethods\Http\Controllers\CbkPaymentController;

Route::get('/vendor/vector/laravel-multi-payment-methods/cbk/payment-view', [CbkPaymentController::class, 'paymentView'])->name('cbk.payment_view');

