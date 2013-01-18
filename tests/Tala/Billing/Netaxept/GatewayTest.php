<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Netaxept;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest, array(
            'username' => 'foo',
            'password' => 'bar',
        ));

        $this->request = new Request;
        $this->request->amount = 1000;
        $this->request->returnUrl = 'https://www.example.com/complete';

        $this->card = new CreditCard;
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('get')->with(m::type('string'))->once()
            ->andReturn('<Response><ResponseCode>OK</ResponseCode><TransactionId>abc123</TransactionId></Response>');

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\RedirectResponse', $response);
        $this->assertEquals('https://epayment.bbs.no/Terminal/Default.aspx?merchantId=foo&transactionId=abc123', $response->getRedirectUrl());
    }

    /**
     * @expectedException \Tala\Exception
     * @expectedExceptionMessage Authentication Error
     */
    public function testPurchaseError()
    {
        $this->httpClient->shouldReceive('get')->with(m::type('string'))->once()
            ->andReturn('<Response><Error><Message>Authentication Error</Message></Error></Response>');

        $response = $this->gateway->purchase($this->request, $this->card);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('get')->with('responseCode')->once()->andReturn('OK');
        $this->httpRequest->shouldReceive('get')->with('transactionId')->once()->andReturn('abc123');

        $this->httpClient->shouldReceive('get')->with(m::type('string'))->once()
            ->andReturn('<Response><ResponseCode>OK</ResponseCode><TransactionId>abc123</TransactionId></Response>');

        $response = $this->gateway->completePurchase($this->request);

        $this->assertInstanceOf('\Tala\Billing\Netaxept\Response', $response);
        $this->assertEquals('abc123', $response->getGatewayReference());
        $this->assertEquals('OK', $response->getMessage());
    }

    /**
     * @expectedException \Tala\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->httpRequest->shouldReceive('get')->with('responseCode')->once()->andReturn(null);

        $response = $this->gateway->completePurchase($this->request);
    }

    /**
     * @expectedException \Tala\Exception
     * @expectedExceptionMessage FAILURE
     */
    public function testCompletePurchaseError()
    {
        $this->httpRequest->shouldReceive('get')->with('responseCode')->once()->andReturn('FAILURE');

        $response = $this->gateway->completePurchase($this->request);
    }
}
