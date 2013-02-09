<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\PaymentExpress;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\CreditCard;
use Tala\Request;

class PxPostGatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new PxPostGateway($this->httpClient, $this->httpRequest);

        $card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));

        $this->options = array(
            'amount' => 1000,
            'card' => $card,
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Transaction Declined
     */
    public function testAuthorizeFailure()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');

        $response = $this->gateway->authorize($this->options);
    }

    public function testCaptureSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->capture($options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    public function testPurchaseSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage Transaction Declined
     */
    public function testPurchaseFailure()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');

        $response = $this->gateway->purchase($this->options);
    }

    public function testRefundSuccess()
    {
        $this->httpClient->shouldReceive('post')
            ->with('https://sec.paymentexpress.com/pxpost.aspx', m::type('string'))->once()
            ->andReturn('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $options = array(
            'amount' => 1000,
            'gatewayReference' => '000000030884cdc6',
        );

        $response = $this->gateway->refund($options);

        $this->assertInstanceOf('\Tala\Response', $response);
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }
}
