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

        /*
          |--------------------------------------------------------------------------
          | CBK Method
          |--------------------------------------------------------------------------
          |
          | Here are credentials for CBK gateway.
          |
          */

        'cbk' => [
            'client_id' => "44977436",
            'client_secret' => "1fpz0XLtxlxnCrZN-l-pMmgIzOrwmd7zoZ_A3gIglks1",
            'encryption_key' => "FrMHBg6TQUihtWELiqcFxJvcTY0dnwGqhsBqyH0C6D2oDlSYvarVpNOkR04pZJ5KJklDN8AKm2RqXohri_29tlBZxP8OT_6GgYG90iSxWs41",
            'merchant_id' => "M391",
            'sandbox' => env('CBK_SAND_BOX', true),
        ],
    ]
];
