<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PaymentExpress;

use Mockery as m;
use Tala\Request;

class PxPayGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new PxPayGateway;

        $this->browser = m::mock('\Buzz\Browser');
        $this->gateway->setBrowser($this->browser);

        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');
        $this->gateway->setHttpRequest($this->httpRequest);

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/complete';
    }

    public function testAuthorizeSuccess()
    {
        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response valid="1"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->authorize($this->request, null);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertEquals('https://www.example.com/redirect', $response->getRedirectUrl());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testAuthorizeError()
    {
        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response valid="0"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->authorize($this->request, null);
    }

    public function testCompleteAuthorizeSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response><Success>1</Success><DpsTxnRef>5</DpsTxnRef></Response>');

        $response = $this->gateway->completeAuthorize($this->request);

        $this->assertInstanceOf('\Tala\PaymentExpress\Response', $response);
        $this->assertEquals(5, $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testCompleteAuthorizeInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn(null);

        $response = $this->gateway->completeAuthorize($this->request);
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Error processing payment
     */
    public function testCompleteAuthorizeError()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response><Success>0</Success><ResponseText>Error processing payment</ResponseText></Response>');

        $response = $this->gateway->completeAuthorize($this->request);
    }

    public function testPurchaseSuccess()
    {
        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response valid="1"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->purchase($this->request, null);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertEquals('https://www.example.com/redirect', $response->getRedirectUrl());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testPurchaseError()
    {
        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response valid="0"><URI>https://www.example.com/redirect</URI></Response>');

        $response = $this->gateway->purchase($this->request, null);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response><Success>1</Success><DpsTxnRef>5</DpsTxnRef></Response>');

        $response = $this->gateway->completePurchase($this->request);

        $this->assertInstanceOf('\Tala\PaymentExpress\Response', $response);
        $this->assertEquals(5, $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn(null);

        $response = $this->gateway->completePurchase($this->request);
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Error processing payment
     */
    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('result')->once()
            ->andReturn('abc123');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', array(), m::type('string'))->once()
            ->andReturn('<Response><Success>0</Success><ResponseText>Error processing payment</ResponseText></Response>');

        $response = $this->gateway->completePurchase($this->request);
    }
}
