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
class Moyasar extends BaseMethod
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
        $this->client->acceptJson()->withBasicAuth($this->config?->secret_key, '');
//        $this->client->withHeaders(["Token" => $this->config->api_key]);
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
        $payment_url = route('moyasar.payment_view', $this->buildPayRequest($details));
        return $this->response(200, true, "success", $payment_url);
    }

    /**
     * Send Payment Request
     *
     * @param array $details
     * @return Illuminate\View\View
     * @throws JsonException
     */
    public function getPayPage(array $details)
    {
        return View::make('payment::moyasar.payment_view', $this->buildPayRequest($details));
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
        $items = $details['items'] ?? [];
        return [
            'transaction_details' => $transactionDetails,
            'publishable_api_key' => $this->config?->publishable_key,
            'callback_url' => $this->config?->callback_url,
            'process_url' => $this->config?->process_url,
            'customer_details' => $customerDetails,
            'items' => $items,
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
    public function getPaymentDetails(string $paymentID): array
    {
        $response = $this->client->get("/v1/payments/$paymentID");
        $jsonResponse = $response->object();
        dd($jsonResponse);
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
