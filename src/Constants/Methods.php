<?php

namespace Vector\LaravelMultiPaymentMethods\Constants;

use Illuminate\Support\Str;

/**
 * SMS Available Methods.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 *
 */
enum Methods: string
{
    // DEFINING CONSTANTS
    case UPAYMENTS = 'upayments';
    case CBK = 'cbk';
    case MOYASAR = 'moyasar';

    public static function getNames(): array
    {
        return array_column(self::cases(), "name");
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), "value");
    }

    public static function keysAndValues(): array
    {
        return array_combine(self::getValues(), self::getNames());
    }

    public static function selectKeysAndValues(): array
    {
        return collect(self::keysAndValues())->map(function ($key, $value) {
            return str_replace('_', ' ', Str::ucfirst(Str::lower($key)));
        })->toArray();
    }

}
