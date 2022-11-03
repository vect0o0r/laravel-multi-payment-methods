# Laravel Multi Payment Methods

Laravel Package For Payment Methods Multi Methods Usage.

## 🚀 About Me

I'm a Full-stack Developer with more than 7 years of a unique experience, I'm a self-learner and specialized in applying
the best practices to design and implement scalable, concurrent, flexible, and robust software solutions, with a healthy
focus on the expected business outcomes, Always I seek to gain new skills and grow up my knowledge.

## Supported Methods

- [x]  [UPAYMENTS](https://www.upayments.com/)
- [ ]  Others On The Way...

## Features

- Pay
- Get Transaction Details
- Refund Payment

## Installation

You Can Install Via Composer

```bash
composer require vectoor/laravel-multi-payment-methods
```

## Publish

You Should Publish Config File To Set Method Credentials

```bash
php artisan vendor:publish --provider="Vector\LaravelMultiPaymentMethods\Providers\PaymentServiceProvider"
```

## Config

Example Of Config File

```
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


```

## Facade Usage

Use Facade

```bash
use Vector\LaravelMultiPaymentMethods\Facade\Payment;
```

# Usage/Examples

## Request

### Upayments

#### To Create Payment

```bash
        $paymentDetails = [
            "transaction" => [
                "id" => 1,
                "price" => 100,
                "currency_code" => "KWD",
                "method" => 'cc'
            ],
            "customer" => [
                "name" => 'Mohamed Khaled',
                "email" => 'mo.khaled.yousef@gmail.com',
                "phone" => '+2001118065363',
            ],
            "items" => [
                [
                    "name" => "test Product 1",
                    "price" => 100,
                    "quantity" => 1,
                ],
                [
                    "name" => "test Product 2",
                    "price" => 120,
                    "quantity" => 4,
                ],
                [
                    "name" => "test Product 3",
                    "price" => 80,
                    "quantity" => 2,
                ]
            ]
        ];
  Payment::driver('upayments')->pay($paymentDetails);
```

## Request

## Response

### Example

```bash
  array:4 [
  "code" : 200
  "success" => true
  "message" => "success"
  "payment_url" => "https://api.upayments.com/live/new-knet-payment?ref=xxxxxxxxxxxx&sess_id=xxxxxxx"
  "data" => array:5 [
    "status" => "success"
    "paymentURL" =>  "https://api.upayments.com/live/new-knet-payment?ref=xxxxxxxxxxxx&sess_id=xxxxxxx"
  ]
]
```

| Variable           | Type     | Description                             |
|:-------------------| :------- |:----------------------------------------|
| `code`             | `integer` | Response Code OF The Sent Api           |
| `message`          | `string`  | The Response Message From Api           |
| `payment_url`      | `string`  | The Payment Url From Api                |
| `success`          | `bool`    | The Response Status (If Success Or Not) |
| `data`             | `array`   | The Full Response From Api              |

## Authors

- [@Vector](https://github.com/vect0o0r)

## 🔗 Links

[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://dev-vector.com)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/mohammed-khaled-yousef/)

## License

The Laravel Payment Methods Gateway package is open-sourced software licensed under
the [MIT license](https://github.com/vect0o0r/laravel-multi-payment-methods/blob/master/LICENSE).

## Support

For support, email mo.khaled.yousef@gmail.com .

