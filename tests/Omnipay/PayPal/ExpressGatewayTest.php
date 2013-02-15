<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

use Omnipay\GatewayTestCase;

class ExpressGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ExpressGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Omnipay\Common\RedirectResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-42721413K79637829', $response->getRedirectUrl());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Common\RedirectResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-42721413K79637829', $response->getRedirectUrl());
    }

    public function testPurchaseFailure()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }
}
