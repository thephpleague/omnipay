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
use Tala\CreditCard;
use Tala\Request;

class PxPostGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = new PxPostGateway;

        $this->browser = m::mock('\Buzz\Browser');
        $this->gateway->setBrowser($this->browser);

        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');
        $this->gateway->setHttpRequest($this->httpRequest);

        $this->card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->request = new Request;
        $this->request->amount = 1000;
    }

    public function testAuthorizeSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $response = $this->gateway->authorize($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Transaction Declined
     */
    public function testAuthorizeFailure()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $response = $this->gateway->authorize($this->request, $this->card);
    }

    public function testCaptureSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $request = new Request;
        $request->amount = 1000;
        $request->gatewayReference = '000000030884cdc6';

        $response = $this->gateway->capture($request);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $response = $this->gateway->purchase($this->request, $this->card);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Transaction Declined
     */
    public function testPurchaseFailure()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $response = $this->gateway->purchase($this->request, $this->card);
    }

    public function testRefundSuccess()
    {
        $browserResponse = m::mock('Buzz\Message\Response');
        $browserResponse->shouldReceive('getContent')->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $this->browser->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', array(), m::type('string'))->once()
            ->andReturn($browserResponse);

        $request = new Request;
        $request->amount = 1000;
        $request->gatewayReference = '000000030884cdc6';

        $response = $this->gateway->refund($request);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }
}
