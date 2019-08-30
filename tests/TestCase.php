<?php

namespace Paynow;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $testHttpClient;

    protected $client;

    public function setUp()
    {
        $this->client = new Client(
            'TestApiKey',
            'TestSignatureKey',
            Environment::SANDBOX
        );
        $this->testHttpClient = new TestHttpClient($this->client->getConfiguration());
    }

    public function loadData($fileName, $asString = false)
    {
        $filePath = dirname(__FILE__) . '/resources/' . $fileName;
        if (!$asString) {
            return json_decode(file_get_contents($filePath), true);
        } else {
            return file_get_contents($filePath);
        }
    }
}