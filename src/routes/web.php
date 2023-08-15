<?php

use Illuminate\Support\Facades\Route;
use Vector\LaravelMultiPaymentMethods\Http\Controllers\CbkPaymentController;
use Vector\LaravelMultiPaymentMethods\Http\Controllers\MoyasarPaymentController;

Route::get('/vendor/vector/laravel-multi-payment-methods/cbk/payment-view', [CbkPaymentController::class, 'paymentView'])->name('cbk.payment_view');
Route::get('/vendor/vector/laravel-multi-payment-methods/moyasar/payment-view', [MoyasarPaymentController::class, 'paymentView'])->name('moyasar.payment_view');

