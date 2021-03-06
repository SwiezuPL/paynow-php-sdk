<?php

namespace Paynow\Tests;

use Paynow\Exception\SignatureVerificationException;
use Paynow\Notification;

class NotificationTest extends TestCase
{
    /**
     * @dataProvider requestsToTest
     */
    public function testVerifyPayloadSuccessfully($payload, $headers)
    {
        // given

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
        $this->assertTrue(true);
    }

    public function requestsToTest()
    {
        $payload = $this->loadData('notification.json', true);
        return [
            [
                $payload,
                ['Signature' => 'UZgTT6iSv174R/OyQ2DWRCE9UCmvdXDS8rbQQcjk+AA=']
            ],
            [
                $payload,
                ['signature' => 'UZgTT6iSv174R/OyQ2DWRCE9UCmvdXDS8rbQQcjk+AA=']
            ]
        ];
    }

    public function testShouldThrowExceptionOnIncorrectSignature()
    {
        // given
        $this->expectException(SignatureVerificationException::class);
        $payload = $this->loadData('notification.json', true);
        $headers = ['Signature' => 'Aq/VmN15rtjVbuy9F7Yw+Ym76H+VZjVSuHGpg4dwitY='];

        // when
        new Notification('s3ecret-k3y', $payload, $headers);

        // then
    }

    public function testShouldThrowExceptionOnMissingPayload()
    {
        // given
        $this->expectException(\InvalidArgumentException::class);
        $payload = null;
        $headers = [];

        // when
        new Notification('s3ecret-k3y', null, null);

        // then
    }

    public function testShouldThrowExceptionOnMissingPayloadHeaders()
    {
        // given
        $this->expectException(\InvalidArgumentException::class);
        $payload = $this->loadData('notification.json', true);
        $headers = null;

        // when
        new Notification('s3ecret-k3y', $payload, null);

        // then
    }
}
