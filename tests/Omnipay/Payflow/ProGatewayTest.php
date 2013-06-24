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

        $this->gateway = new ProGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
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
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getTransactionReference());
    }

    public function testAuthorizeError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('User authentication failed', $response->getMessage());
    }

    public function testCapture()
    {
        $options = array(
            'amount' => '10.00',
            'transactionReference' => 'abc123',
        );

        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->capture($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getTransactionReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getTransactionReference());
    }

    public function testPurchaseError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('User authentication failed', $response->getMessage());
    }

    public function testRefund()
    {
        $options = array(
            'amount' => '10.00',
            'transactionReference' => 'abc123',
        );

        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->refund($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getTransactionReference());
    }
}
