<?php

namespace Vector\LaravelMultiPaymentMethods\Methods;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use JsonException;

/**
 * Driver class.
 *
 * @author Vector <mo.khaled.yousef@gmail.com>
 */
abstract class BaseMethod
{
    /**
     * requiredConfigKeys.
     *
     * @var array
     */
    protected array $requiredConfigKeys;
    /**
     * supportedTypes.
     *
     * @var array
     */
    protected array $supportedTypes;
    /**
     * Sandbox.
     *
     * @var bool
     */
    protected bool $sandbox = false;
    /**
     * Driver.
     *
     * @var string
     */
    protected string $driver;
    /**
     * base_url.
     *
     * @var string
     */
    protected string $base_url;
    /**
     * test_base_url.
     *
     * @var string
     */
    protected string $test_base_url;
    /**
     * live_base_url.
     *
     * @var string
     */
    protected string $live_base_url;
    /**
     * config.
     *
     * @var object
     */
    protected object $config;
    /**
     * config.
     *
     * @var PendingRequest
     */
    protected PendingRequest $client;

    /**
     * Boot Method And Set Method Configurations And HttpClient
     *
     * @return void
     * @throws JsonException
     */
    public function __construct()
    {
        //Set Method Configurations From Config File
        $this->setConfigurations();
        //Set Mode
        $this->setMode();
        //Set Base Url
        $this->setBaseUrl();
        //Start Creating Http Client To Send Request
        $this->buildHttpClient();
        //validate Required Keys Is Exists
        $this->validateRequiredKeys();
    }

    /**
     * Set Method Configurations From Config File
     *
     * @return void
     */
    protected function setConfigurations(): void
    {
        $this->config = (object)config("payment-methods.methods.$this->driver");
    }

    /**
     * Set Method Base Url
     *
     * @return void
     */
    protected function setBaseUrl(): void
    {
        $this->base_url = $this->sandbox ? $this->test_base_url : $this->live_base_url;
    }

    /**
     * Set Method Mode (Sandbox Or Live)
     *
     * @return void
     */
    protected function setMode(): void
    {

        $this->sandbox = (bool)($this->config->sandbox ?? false);
    }

    /**
     * Check The Required Keys Is Initialized
     *
     * @return array
     * @throws JsonException
     */
    protected function validateRequiredKeys(): array
    {
        $config = $this->config;
        $requiredKeys = $this->requiredConfigKeys;
        $errors = [];
        foreach ($requiredKeys as $requiredKey) {
            if (!isset($config->$requiredKey) || is_null($config->$requiredKey) || trim($config->$requiredKey) === '') {
                throw new JsonException("$requiredKey is required");
            }
        }

        return $errors;
    }

    /**
     * Start Creating Http Client To Send Request
     *
     * @return PendingRequest
     */
    protected function buildHttpClient(): PendingRequest
    {
        return $this->client = Http::baseUrl($this->base_url)->acceptJson();
    }

    /**
     * Return The Response Of The Method Action
     *
     * @param int $code
     * @param bool $success
     * @param string $message
     * @param string|null $payment_url
     * @param array|null $data
     * @return array
     */
    public function response(int $code, bool $success, string $message, string $payment_url = null, array $data = null): array
    {
        return ["code" => $code, "success" => $success, "message" => $message, "payment_url" => $payment_url, "data" => $data];
    }

    /**
     * Convert Soap To Json
     *
     * @param $soap
     * @return mixed
     * @throws JsonException
     */
    public function soapToJson($soap): mixed
    {
        // Load xml data into xml data object
        $xmldata = simplexml_load_string($soap);
        // using json_encode function && Encode this xml data into json
        return json_decode(json_encode($xmldata, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);
    }
}
