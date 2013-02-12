<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PaymentExpress;

use Mockery as m;
use Omnipay\BaseGatewayTest;
use Omnipay\Request;

class PxPayGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new PxPayGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response valid="1"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.example.com/redirect', $response->getRedirectUrl());
    }

    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testAuthorizeError()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response valid="0"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->authorize($this->options);
    }

    public function testCompleteAuthorizeSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response><Success>1</Success><DpsTxnRef>5</DpsTxnRef></Response>');

        $response = $this->gateway->completeAuthorize($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(5, $response->getGatewayReference());
    }

    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testCompleteAuthorizeInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn(null);

        $response = $this->gateway->completeAuthorize($this->options);
    }

    public function testCompleteAuthorizeError()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response><Success>0</Success><ResponseText>Error processing payment</ResponseText></Response>');

        $response = $this->gateway->completeAuthorize($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Error processing payment', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response valid="1"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.example.com/redirect', $response->getRedirectUrl());
    }

    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testPurchaseError()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response valid="0"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->purchase($this->options);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response><Success>1</Success><DpsTxnRef>5</DpsTxnRef></Response>');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(5, $response->getGatewayReference());
    }

    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn(null);

        $response = $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', m::type('string'))->once()
            ->andReturn('<Response><Success>0</Success><ResponseText>Error processing payment</ResponseText></Response>');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Error processing payment', $response->getMessage());
    }
}
