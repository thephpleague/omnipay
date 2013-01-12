<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payflow;

use Mockery as m;
use Tala\CreditCard;
use Tala\Request;

class ProGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new ProGateway;

        $this->browser = m::mock('\Buzz\Browser');
        $this->gateway->setBrowser($this->browser);

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->request = new Request();
        $this->request->amount = 1000;
    }

    public function testAuthorizeSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->authorize($this->request, $this->card);

        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage User authentication failed
     */
    public function testAuthorizeError()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=1&RESPMSG=User authentication failed');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->authorize($this->request, $this->card);
    }

    public function testCapture()
    {
        $request = new Request();
        $request->amount = 1000;
        $request->gatewayReference = 'abc123';

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->capture($request);

        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage User authentication failed
     */
    public function testPurchaseError()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=1&RESPMSG=User authentication failed');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->purchase($this->request, $this->card);
    }

    public function testRefund()
    {
        $request = new Request();
        $request->amount = 1000;
        $request->gatewayReference = 'abc123';

        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->browser->shouldReceive('post')->once()
            ->with('https://payflowpro.paypal.com', array(), m::type('string'))
            ->andReturn($browserResponse);

        $response = $this->gateway->refund($request);

        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
    }
}
