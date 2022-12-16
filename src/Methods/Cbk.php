<?php

namespace Vector\LaravelMultiPaymentMethods\Methods;

use JsonException;
use Vector\LaravelMultiPaymentMethods\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\View;

/**
 * CBK Payment Method.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Cbk extends BaseMethod implements PaymentGatewayInterface
{

    /**
     * Set Method Driver And Base Url
     *
     * @return void
     * @throws JsonException
     */

    /**
     * Access Token.
     *
     * @var string
     */
    protected string $accessToken;

    public function __construct()
    {
        //Set Method Driver
        $this->driver = 'cbk';
        //Set Method Live Base Url
        $this->live_base_url = $this->base_url = "https://pg.cbk.com";
        //Set Method test Base Url
        $this->test_base_url = "https://pgtest.cbk.com";
        //Set Config Required Keys
        $this->requiredConfigKeys = ['client_id', 'encryption_key', 'merchant_id', 'encryption_key', 'client_secret'];
        //Calling Parent Constructor
        parent::__construct();
        //Init Http Client With Additional Configs
        $this->client->acceptJson()->withBasicAuth($this->config?->client_id, $this->config?->client_secret);
//        $this->client->withHeaders(["Token" => $this->config->api_key]);
    }

    /**
     * Generating Authenticate Key
     *
     * @return string
     * @throws JsonException
     */
    public function generateAuthKey(): string
    {
        $details = ["ClientId" => $this->config?->client_id, "ClientSecret" => $this->config?->client_secret, "ENCRP_KEY" => $this->config?->encryption_key];
        $response = $this->client->withBasicAuth($this->config?->client_id, $this->config?->client_secret)->post("ePay/api/cbk/online/pg/merchant/Authenticate", $details);
        $jsonResponse = $response->object();
        if (!$response->ok() || $response->status() !== 200 || $jsonResponse->Status != 1)
            throw new JsonException("FAiled To Get Access Token (Invalid Provided Credentials)");
        $this->accessToken = $jsonResponse->AccessToken;
        return $this->accessToken;
    }

    /**
     * Send Payment Request
     *
     * @param array $details
     * @return array
     * @throws JsonException
     */
    public function pay(array $details): array
    {
        $this->generateAuthKey();
        $payment_url = route('cbk.payment-view', $this->buildPayRequest($details));
        return $this->response(200, true, "success", $payment_url);
        $message = $success ? ($jsonResponse->status ?? null) : ($jsonResponse->error_msg ?? null);

        dd($payment_url);
        return View::make('payment::cbkPaymentView.blade', ['details' => $this->buildPayRequest($details)]);
        $response = $this->client->post("/ePay/pg/epay?_v={$this->accessToken}", $this->buildPayRequest($details));
        $jsonResponse = $response->object();
        dd($response->body(), $response->status());
        $success = $response->status() === 200 && $jsonResponse->status === "success";
        $message = $success ? ($jsonResponse->status ?? null) : ($jsonResponse->error_msg ?? null);
        $payment_url = $jsonResponse->paymentURL ?? null;
        return $this->response($response->status(), $success, $message, $payment_url, (array)$jsonResponse);
    }

    /**
     * Build Payment Request
     *
     * @param array $details
     * @return array
     */
    public function buildPayRequest(array $details): array
    {
        $transactionDetails = $details['transaction'] ?? [];
        $customerDetails = $details['customer'] ?? [];
        $productDetails = $details['items'] ?? [];
        return [
            'tij_MerchantEncryptCode' => $this->config?->encryption_key,
            'tij_MerchAuthKeyApi' => $this->accessToken,
            'tij_MerchantPaymentLang' => 'en',
            'tij_MerchantPaymentAmount' => $transactionDetails['price'] ?? null,
            'tij_MerchantPaymentTrack' => $transactionDetails['id'] ? $transactionDetails['id'] . '_' . time() : null,
            'tij_MerchantPaymentRef' => $transactionDetails['id'] ? $transactionDetails['id'] . '_' . time() : null,
            'MerchantPaymentCurrency' => $transactionDetails['currency_code'] ?? null,
            'tij_MerchPayType' => $transactionDetails['method'] ?? 1,
            'tij_MerchReturnUrl' => $this->config->return_url,
            'tij_MerchantUdf1' => $transactionDetails['id'] ?? null,
        ];
    }

    /**
     * get Payment Details
     *
     * @param string $orderID
     * @return array
     */
    public function getPaymentDetails(string $orderID): array
    {
        $response = $this->client->asForm()->baseUrl("https://statusapi.upayments.com")->post("api/check/payment/status", ['merchant_id' => $this->config->merchant_id, 'order_id' => $orderID]);
        $jsonResponse = $response->object();
        $success = $response->status() === 200 && $jsonResponse->status === "success";
        $message = $success ? ($jsonResponse->status ?? null) : ($jsonResponse->error_msg ?? null);
        $payment_url = $jsonResponse->paymentURL ?? null;
        return $this->response($response->status(), $success, $message, $payment_url, (array)$jsonResponse);
    }

    /**
     * Validate Response CallBack
     *
     * @param array $request
     * @return bool
     */
    public function validateResponseCallBack(array $request): bool
    {
        return true;
    }

    /**
     * Response CallBack
     *
     * @param array $responseDetails
     * @return array
     */
    public function responseCallBack(array $responseDetails): array
    {
        $isValid = $this->validateResponseCallBack($responseDetails);
        if (!$isValid)
            return $this->response(400, false, "Payment Failed", null, $responseDetails);
        $objectResponse = (object)$responseDetails;
        $success = $objectResponse->Result === 'CAPTURED';
        $status = $success ? 200 : 400;
        $message = $objectResponse->Result;
        return $this->response($status, $success, $message, null, $responseDetails);
    }

}
