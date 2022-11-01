<?php

namespace Vector\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Vector\LaravelMultiPaymentMethods\Providers\PaymentServiceProvider;

/**
 * TestCase class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
abstract class TestCase extends Orchestra
{
    /**
     * Get package service providers.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            PaymentServiceProvider::class,
        ];
    }

    /**
     * Get Customer Details
     *
     * @return array
     */
    public function getCustomerDetails(): array
    {
        return [
            "name" => 'Mohamed Khaled',
            "email" => 'mo.khaled.yousef@gmail.com',
            "phone" => '01118065363',
        ];
    }

    /**
     * Get Transaction Details
     *
     * @return array
     */
    public function getTransactionDetails(): array
    {
        return [
            "id" => 1,
            "price" => 100,
            "currency_code" => "KWD",
            "method" => 'cc',
        ];
    }

    /**
     * Get Transaction Details
     *
     * @return array
     */
    public function getItemsDetails(): array
    {
        return [
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
        ];
    }
}
