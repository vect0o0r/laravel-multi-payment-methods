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
        $payment_url = route('cbk.payment_view', $this->buildPayRequest($details));
        return $this->response(200, true, "success", $payment_url, ['access_token' => $this->accessToken]);
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
            'url' => $this->base_url,
            'tij_MerchantEncryptCode' => $this->config?->encryption_key,
            'tij_MerchAuthKeyApi' => $this->accessToken,
            'tij_MerchantPaymentLang' => 'en',
            'tij_MerchantPaymentAmount' => $transactionDetails['price'],
            'tij_MerchantPaymentTrack' => $transactionDetails['id'],
            'tij_MerchantPaymentRef' => $transactionDetails['id'],
            'MerchantPaymentCurrency' => $transactionDetails['currency_code'],
            'tij_MerchPayType' => $transactionDetails['method'] ?? 1,
            'tij_MerchReturnUrl' => $transactionDetails['return_url'] ?? 1,
            'tij_MerchantUdf1' => $transactionDetails['id'] ?? null,
            'tij_MerchantUdf2' => "",
            'tij_MerchantUdf3' => "",
            'tij_MerchantUdf4' => "",
            'tij_MerchantUdf5' => "",
        ];
    }

    /**
     * get Payment Details
     *
     * @param string $orderID
     * @param string|null $accessToken
     * @return array
     * @throws JsonException
     */
    public function getPaymentDetails(string $orderID, string $accessToken = null): array
    {
        $requestDetails = ['authkey' => $accessToken, 'encrypmerch' => $this->config?->encryption_key, 'payid' => $orderID];
        $response = $this->client->withBasicAuth($this->config?->client_id, $this->config?->client_secret)->post("ePay/api/cbk/online/pg/Verify", $requestDetails);
        $jsonResponse = $response->object();
        if ($response->status() !== 200 && $jsonResponse->Status != -1 && $jsonResponse->Status != 0)
            throw new JsonException("FAiled To Get Access Token (Invalid authkey OR Pay ID Credentials)");
        return $this->response($response->status(), ($jsonResponse->Status == 1), $jsonResponse->Message, null, (array)$jsonResponse);
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
     * @throws JsonException
     */
    public function responseCallBack(array $responseDetails): array
    {
        $isValid = $this->validateResponseCallBack($responseDetails);
        if (!$isValid)
            return $this->response(400, false, "Payment Failed", null, $responseDetails);
        $paymentDetails = $this->getPaymentDetails($responseDetails['pay_id'], $responseDetails['access_token']);
        return $this->response($paymentDetails['code'], $paymentDetails['success'], $paymentDetails['message'], null, $paymentDetails['data']);
    }

}
