<?php

namespace Vector\LaravelMultiPaymentMethods\Interfaces;

use Exception;
use JsonException;

/**
 * SmsGatewayInterface interface.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
interface PaymentGatewayInterface
{
    /**
     * Send sms message.
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function pay(array $details): array;
}
