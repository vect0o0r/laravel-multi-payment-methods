<?php

namespace Vector\LaravelMultiPaymentMethods\Providers;

use Illuminate\Support\ServiceProvider;
use Vector\LaravelMultiPaymentMethods\Methods\Managers\PaymentManager;

/**
 * PaymentServiceProvider class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/payment-methods.php' => config_path('payment-methods.php')], 'laravel-multi-payment-methods');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../../config/payment-methods.php', 'payment-methods');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/payments', 'payment');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
//        $this->publishes([__DIR__ . '/../resources/views/payments' => resource_path('views/vendor/vector/payments')]);
        $this->app->bind('payment', function () {
            return new PaymentManager();
        });
    }
}
