<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\WorldPay;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->initialize(array(
            'callbackPassword' => 'bar123',
        ));

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/complete';

        $this->card = new CreditCard;
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertContains('https://secure.worldpay.com/wcc/purchase?', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar123');
        $this->httpRequest->shouldReceive('get')->with('transStatus')->once()->andReturn('Y');
        $this->httpRequest->shouldReceive('get')->with('transId')->once()->andReturn('abc123');

        $response = $this->gateway->completePurchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('abc123', $response->getGatewayReference());
    }

    /**
     * @expectedException \Tala\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalidCallbackPassword()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar321');

        $response = $this->gateway->completePurchase($this->request, $this->card);
    }

    /**
     * @expectedException \Tala\Exception
     * @expectedExceptionMessage Declined
     */
    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('callbackPW')->once()->andReturn('bar123');
        $this->httpRequest->shouldReceive('get')->with('transStatus')->once()->andReturn('N');
        $this->httpRequest->shouldReceive('get')->with('rawAuthMessage')->once()->andReturn('Declined');

        $response = $this->gateway->completePurchase($this->request, $this->card);
    }
}
