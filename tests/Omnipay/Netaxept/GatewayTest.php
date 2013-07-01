<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('foo');
        $this->gateway->setPassword('bar');

        $this->options = array(
            'amount' => '10.00',
            'currency' => 'NOK',
            'transactionId' => '123',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('f3d94dd5c0f743a788fc943402757c58', $response->getTransactionReference());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertSame('https://epayment.bbs.no/Terminal/Default.aspx?merchantId=foo&transactionId=f3d94dd5c0f743a788fc943402757c58', $response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame("Missing parameter: 'Order Number'", $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->query->replace(
            array(
                'responseCode' => 'OK',
                'transactionId' => 'abc123',
            )
        );

        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('8a88d40cab5b47fab25e24d6228180a7', $response->getTransactionReference());
        $this->assertSame('OK', $response->getMessage());
    }

    public function testCompletePurchaseCancel()
    {
        $this->getHttpRequest()->query->replace(
            array(
                'transactionId' => '1de59458487344759832716abf48109b',
                'responseCode' => 'Cancel',
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('1de59458487344759832716abf48109b', $response->getTransactionReference());
        $this->assertEquals('Cancel', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $this->getHttpRequest()->query->replace(
            array(
                'responseCode' => 'OK',
                'transactionId' => 'abc123',
            )
        );

        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Unable to find transaction', $response->getMessage());
    }
}
