<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setCallbackPassword('bar123');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Common\RedirectResponse', $response);
        $this->assertTrue($response->isRedirect());
        $this->assertContains('https://secure.worldpay.com/wcc/purchase?', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar123');
        $this->httpRequest->shouldReceive('get')->with('transStatus')->once()->andReturn('Y');
        $this->httpRequest->shouldReceive('get')->with('transId')->once()->andReturn('abc123');
        $this->httpRequest->shouldReceive('get')->with('rawAuthMessage')->once()->andReturn(null);

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc123', $response->getGatewayReference());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalidCallbackPassword()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar321');

        $response = $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar123');
        $this->httpRequest->shouldReceive('get')->with('transStatus')->once()->andReturn('N');
        $this->httpRequest->shouldReceive('get')->with('transId')->once()->andReturn(null);
        $this->httpRequest->shouldReceive('get')->with('rawAuthMessage')->once()->andReturn('Declined');

        $response = $this->gateway->completePurchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Declined', $response->getMessage());
    }
}
