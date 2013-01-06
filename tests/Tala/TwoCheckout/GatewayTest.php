<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\TwoCheckout;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new Gateway(array('username' => 'abc', 'password' => '123'));

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/complete';

        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');
        $this->gateway->setHttpRequest($this->httpRequest);
    }

    public function testPurchase()
    {
        $source = new CreditCard;
        $response = $this->gateway->purchase($this->request, $source);

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

        $this->gateway->completePurchase($this->request);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('order_number')->once()
            ->andReturn(5);

        $this->httpRequest->shouldReceive('get')->with('key')->once()
            ->andReturn(strtoupper(md5('123abc510.00')));

        $this->gateway->completePurchase($this->request);
    }
}
