<?php

namespace Vector\LaravelMultiPaymentMethods\Methods;

use JsonException;
use Vector\LaravelMultiPaymentMethods\Interfaces\PaymentGatewayInterface;

/**
 * SmsBox class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Upayment extends BaseMethod implements PaymentGatewayInterface
{

    /**
     * Set Method Driver And Base Url
     *
     * @return void
     * @throws JsonException
     */
    public function __construct()
    {
        //Set Method Driver
        $this->driver = 'upayment';
        //Set Method Live Base Url
        $this->live_base_url = $this->base_url = "https://api.upayments.com";
        //Set Method test Base Url
        $this->test_base_url = "https://api.upayments.com";
        //Set Config Required Keys
        $this->requiredConfigKeys = ['merchant_id', 'username', 'password', 'api_key'];
        //Calling Parent Constructor
        parent::__construct();
        //Init Http Client With Additional Configs
        $this->client->withHeaders(["x-Authorization" => $this->config->api_key]);
    }


    /**
     * Send sms message.
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function pay(array $details): array
    {
        $subDomain = $this->sandbox ? "test-payment" : "payment-request";
        $response = $this->client->post($subDomain, $this->buildPayRequest($details));
        $jsonResponse = $response->object();
        $success = $response->status() === 200 && $jsonResponse->status === "success";
        $message = $success ? ($jsonResponse->status ?? null) : ($jsonResponse->error_msg ?? null);
        $payment_url = $jsonResponse->paymentURL ?? null;
        return $this->response($response->status(), $success, $message, $payment_url, (array)$jsonResponse);
    }

    /**
     * Send sms message.
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function buildPayRequest(array $details): array
    {

        $transactionDetails = $details['transaction'] ?? [];
        $customerDetails = $details['customer'] ?? [];
        $productDetails = $details['items'] ?? [];
        return [
            "merchant_id" => $this->config->merchant_id,
            "username" => $this->config->username,
            "password" => stripslashes($this->config->password),
            "api_key" => bcrypt($this->config->api_key),
            "success_url" => $this->config->success_url,
            "error_url" => $this->config->error_url,
            "test_mode" => $this->sandbox ? 1 : 0,
            "order_id" => $transactionDetails['id'] ? $transactionDetails['id'] . '_' . time() : null,
            "total_price" => $transactionDetails['price'] ?? null,
            "CurrencyCode" => $transactionDetails['currency_code'] ?? null,
            "whitelabled" => $transactionDetails['whitelabled'] ?? null,
            "reference" => $transactionDetails['id'],
            "payment_gateway" => $transactionDetails['method'] ?? "cc",
            "CstFName" => $customerDetails['name'] ?? null,
            "CstEmail" => $customerDetails['email'] ?? null,
            "CstMobile" => $customerDetails['phone'] ?? null,
            "ProductName" => json_encode(collect($productDetails)->pluck('name')->toArray(), JSON_THROW_ON_ERROR),
            "ProductPrice" => json_encode(collect($productDetails)->pluck('price')->toArray(), JSON_THROW_ON_ERROR),
            "ProductQty" => json_encode(collect($productDetails)->pluck('quantity')->toArray(), JSON_THROW_ON_ERROR),
        ];
    }

}
