<?php

namespace Paynow\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Paynow\Configuration;
use Paynow\Exception\PaynowException;
use Paynow\Util\SignatureCalculator;

class HttpClient implements HttpClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * HttpClient constructor.
     *
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->client = new Client(
            [
            'base_url' => $this->config->getUrl(),
            'timeout' => 30.0,
            'defaults' => [
                'headers' => [
                    'Api-Key' => $this->config->getApiKey(),
                    'User-Agent' => Configuration::USER_AGENT,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]
            ]
        );
    }

    /**
     * @param $url
     * @param $data
     * @param null $idempotencyKey
     * @return ApiResponse
     * @throws HttpClientException
     * @throws \Paynow\Exception\ConfigurationException
     */
    public function post($url, $data, $idempotencyKey = null)
    {
        $options = [
            'json' => $data,
            'headers' => [
                'Signature' => (string)new SignatureCalculator($this->config->getSignatureKey(), $data)
            ]
        ];

        if ($idempotencyKey) {
            $options['headers']['Idempotency-Key'] = $idempotencyKey;
        }

        try {
            return new ApiResponse($this->client->post($url, $options));
        } catch (RequestException $e) {
            throw new HttpClientException(
                "Error occurred during processing request",
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getBody()->getContents()
            );
        }
    }

    /**
     * @param  $url
     * @return ApiResponse
     * @throws HttpClientException
     */
    public function get($url)
    {
        try {
            return new ApiResponse($this->client->get($url));
        } catch (RequestException $e) {
            throw new HttpClientException(
                "Error occurred during processing request",
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getBody()->getContents()
            );
        }
    }
}