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

        $this->gateway = new PxPostGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('PxPostPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => '10.00',
            'transactionReference' => '000000030884cdc6',
        );

        $response = $this->gateway->capture($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getTransactionReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PxPostPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => '10.00',
            'transactionReference' => '000000030884cdc6',
        );

        $response = $this->gateway->refund($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getTransactionReference());
    }

    public function testCreateCardSuccess()
    {
        $this->setMockHttpResponse('PxPostCreateCardSuccess.txt');
        $response = $this->gateway->createCard($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('00000001040c73ea', $response->getTransactionReference());
        $this->assertSame('0000010009328404', $response->getCardReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testCreateCardFailure()
    {
        $this->setMockHttpResponse('PxPostCreateCardFailure.txt');
        $response = $this->gateway->createCard($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('An Invalid Card Number was entered. Check the card number', $response->getMessage());
    }
}
