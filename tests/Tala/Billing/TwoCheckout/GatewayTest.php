<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\TwoCheckout;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setUsername('abc');
        $this->gateway->setPassword('123');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $source = new CreditCard;
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertContains('https://www.2checkout.com/checkout/purchase?', $response->getRedirectUrl());
    }

    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('order_number')->once()
            ->andReturn(5);

        $this->httpRequest->shouldReceive('get')->with('key')->once()
            ->andReturn('ZZZ');

        $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('order_number')->once()
            ->andReturn(5);

        $this->httpRequest->shouldReceive('get')->with('key')->once()
            ->andReturn(strtoupper(md5('123abc510.00')));

        $this->gateway->completePurchase($this->options);
    }
}
