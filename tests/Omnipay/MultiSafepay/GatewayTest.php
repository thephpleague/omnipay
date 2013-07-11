<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccountId('111111');
        $this->gateway->setSiteId('222222');
        $this->gateway->setSiteCode('333333');

        $this->options = array(
            'transactionId' => '123456',
            'currency' => 'EUR',
            'amount' => '100.00',
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'card' => array(
                'email' => 'something@example.com',
            )
        );
    }

    public function testPurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\PurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $request->getCard()->getEmail());
    }

    public function testPurchaseResponse()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        /** @var \Omnipay\MultiSafepay\Message\PurchaseResponse $response */
        $response = $this->gateway->purchase($this->options)->send();

        $paymentUrl = 'https://testpay.multisafepay.com/pay/?transaction=1373536347Hz4sFtg7WgMulO5q123456&lang=';

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals($paymentUrl, $response->getRedirectUrl());
        $this->assertEquals('123456', $response->getTransactionReference());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testPurchaseResponseError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        try {
            $this->gateway->purchase($this->options)->send();
        } catch (InvalidResponseException $e) {
            $this->assertEquals('Invalid amount', $e->getMessage());
            $this->assertEquals(1001, $e->getCode());

            // Rethrow so that the expectedException annotation can do its thing
            throw $e;
        }
    }

    public function testCompletePurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase($this->options);

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\CompletePurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $request->getCard()->getEmail());
    }

    public function testCompletePurchaseResponse()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        /** @var \Omnipay\MultiSafepay\Message\CompletePurchaseResponse $response */
        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('123456', $response->getTransactionReference());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseResponseError()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        try {
            $this->gateway->completePurchase($this->options)->send();
        } catch (InvalidResponseException $e) {
            $this->assertEquals('Back-end: missing data', $e->getMessage());
            $this->assertEquals(1016, $e->getCode());

            // Rethrow so that the expectedException annotation can do its thing
            throw $e;
        }
    }
}
