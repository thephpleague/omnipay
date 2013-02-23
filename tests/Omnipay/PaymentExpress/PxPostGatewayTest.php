<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress;

use Omnipay\GatewayTestCase;

class PxPostGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxPostGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'card' => $this->getValidCard(),
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getGatewayReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->capture($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getGatewayReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->refund($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }
}
