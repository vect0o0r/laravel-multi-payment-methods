<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Available Methods
    |--------------------------------------------------------------------------
    / @author Vector <mo.khaled.yousef@gmail.com>
    | Here are each of the Available Payment Methods
    |
    */

    'methods' => [
        /*
           |--------------------------------------------------------------------------
           | Upayments Method
           |--------------------------------------------------------------------------
           |
           | Here are credentials for Upayments gateway.
           |
           */
        'upayments' => [
            'username' => env('UPAYMENTS_USERNAME', 'test'),
            'password' => env('UPAYMENTS_PASSWORD', 'test'),
            'api_key' => env('UPAYMENTS_API_KEY', 'jtest123'),
            'merchant_id' => env('UPAYMENTS_MERCHANT_ID', '1201'),
            'success_url' => env('UPAYMENTS_SUCCESS_URL', 'http://127.0.0.1:8000/success_url'),
            'error_url' => env('UPAYMENTS_ERROR_URL', 'http://127.0.0.1:8000/error_url'),
            'notify_url' => env('UPAYMENTS_NOTIFY_URL', 'http://127.0.0.1:8000/notify_url'),
            'sandbox' => env('UPAYMENTS_SAND_BOX', true),
        ],
    ]
];
