<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Payflow;

use Omnipay\Common\CreditCard;
use Omnipay\GatewayTestCase;

class ProGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ProGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'card' => new CreditCard(array(
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2016',
                'cvv' => '123',
            )),
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    public function testAuthorizeError()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('User authentication failed', $response->getMessage());
    }

    public function testCapture()
    {
        $options = array(
            'amount' => 1000,
            'gatewayReference' => 'abc123',
        );

        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->capture($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    public function testPurchaseError()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('User authentication failed', $response->getMessage());
    }

    public function testRefund()
    {
        $options = array(
            'amount' => 1000,
            'gatewayReference' => 'abc123',
        );

        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->refund($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }
}
