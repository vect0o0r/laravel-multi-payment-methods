<?php

namespace Vector\LaravelMultiPaymentMethods\Methods;

use JsonException;
use Vector\LaravelMultiPaymentMethods\Interfaces\PaymentGatewayInterface;
use Illuminate\Support\Facades\View;

/**
 * Moyasar Payment Method.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
class Moyasar extends BaseMethod implements PaymentGatewayInterface
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
        $this->driver = 'moyasar';
        //Set Method Live Base Url
        $this->live_base_url = $this->base_url = "https://api.moyasar.com";
        //Set Method test Base Url
        $this->test_base_url = "https://api.moyasar.com";
        //Set Config Required Keys
        $this->requiredConfigKeys = ['publishable_key', 'secret_key'];
        //Calling Parent Constructor
        parent::__construct();
        //Init Http Client With Additional Configs
        $this->client->acceptJson()->withBasicAuth($this->config?->publishable_key, $this->config?->secret_key);
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
        dd('hhh');
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
//        dd('hhhhhh');
//        $this->generateAuthKey();
//        $payment_url = route('cbk.payment_view', $this->buildPayRequest($details));
        $payment_url = route('cbk.payment_view');
        return $this->response(200, true, "success", $payment_url);
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
            'id' => $transactionDetails['id'], // payment’s unique ID.
            'status' => $transactionDetails['id'], // payment status. (default: initiated)
            'amount' => $transactionDetails['price'], // A positive integer in the smallest currency unit
//            'fee' => $transactionDetails['fee'], // transaction fee in halals.
            'currency' => $transactionDetails['currency_code'], // 3-letter ISO code for currency. E.g., SAR, CAD, USD. (default: SAR)
            'refunded' => 0, // refunded amount in halals. (default: 0)
            'refunded_at' => null, // datetime of refunded. (default: null)
            'captured' => 0, // captured amount in halals. (default: 0)
            'captured_at' => 0, // datetime of authroized payment captured. (default: null)
            'voided_at' => null, // datetime of voided. (default: null)
            'description' => null, // payment description
            'invoice_id' => null, // ID of the invoice this payment is for if one exists.(default: null)
            'ip' => null, // User IP
            'callback_url' => null, // page url in customer’s site for final redirection. (used for creditcard 3-D secure and form payment)
            'created_at' => null, // creation timestamp in ISO 8601 format.
            'updated_at' => null, // modification timestamp in ISO 8601 format.
            'metadata' => null, // metadata object (default: null)
            'source' => null, // source object defined the type of payment.


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
