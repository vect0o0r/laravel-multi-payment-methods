<?php

namespace Vector\LaravelMultiPaymentMethods\Facade;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     * @author Vector <mo.khaled.yousef@gmail.com>
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
