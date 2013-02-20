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

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setMerchantId('foo');
        $this->gateway->setToken('bar');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Common\Message\RedirectResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://epayment.bbs.no/Terminal/Default.aspx?merchantId=foo&transactionId=abc123', $response->getRedirectUrl());
    }

    public function testPurchaseError()
    {
        $this->setMockResponse($this->httpClient, 'PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Authentication Error', $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->request->replace(
            array(
                'responseCode' => 'OK',
                'transactionId' => 'abc123',
            )
        );

        $this->setMockResponse($this->httpClient, 'CompletePurchaseSuccess.txt');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc123', $response->getGatewayReference());
        $this->assertEquals('OK', $response->getMessage());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->httpRequest->request->replace(
            array(
                'responseCode' => '',
            )
        );

        $response = $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseError()
    {
        $this->httpRequest->request->replace(
            array(
                'responseCode' => 'FAILURE',
            )
        );

        $response = $this->gateway->completePurchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('FAILURE', $response->getMessage());
    }
}
