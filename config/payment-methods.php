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
           | Upayment Method
           |--------------------------------------------------------------------------
           |
           | Here are credentials for Upayment gateway.
           |
           */
        'upayment' => [
            'username' => env('UPAYMENT_USERNAME', 'test'),
            'password' => env('UPAYMENT_PASSWORD', 'test'),
            'api_key' => env('UPAYMENT_API_KEY', 'jtest123'),
            'merchant_id' => env('UPAYMENT_MERCHANT_ID', '1201'),
            'success_url' => env('UPAYMENT_SUCCESS_URL', 'http://127.0.0.1:8000/success_url'),
            'error_url' => env('UPAYMENT_ERROR_URL', 'http://127.0.0.1:8000/error_url'),
            'notify_url' => env('UPAYMENT_NOTIFY_URL', 'http://127.0.0.1:8000/notify_url'),
            'sandbox' => env('UPAYMENT_SAND_BOX', true),
        ],

    ]

];
