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
use Omnipay\Common\CreditCard;

class PxPostGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxPostGateway($this->httpClient, $this->httpRequest);

        $card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->options = array(
            'amount' => 1000,
            'card' => $card,
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Transaction Declined', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->capture($options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testPurchaseFailure()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Transaction Declined', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->refund($options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }
}
