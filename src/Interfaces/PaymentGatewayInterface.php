<?php

namespace Vector\LaravelMultiPaymentMethods\Interfaces;

use Exception;
use JsonException;

/**
 * Payment Gateway interface.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
interface PaymentGatewayInterface
{
    /**
     * Send Payment Request
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function pay(array $details): array;

    /**
     * Build Payment Request
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function buildPayRequest(array $details): array;

    /**
     * get Payment Details
     *
     * @param string $orderID
     * @return array
     */
    public function getPaymentDetails(string $orderID): array;

    /**
     * Validate Response CallBack
     *
     * @param array $request
     * @return bool
     */
    public function validateResponseCallBack(array $request): bool;

    /**
     * Response CallBack
     *
     * @param array $responseDetails
     * @return array
     */
    public function responseCallBack(array $responseDetails): array;
}
