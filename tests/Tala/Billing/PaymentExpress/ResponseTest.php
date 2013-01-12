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

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function getSuccessResponse()
    {
        return new Response('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');
    }

    public function getDeclinedResponse()
    {
        return new Response('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');
    }

    public function testEmptyConstructor()
    {
        $this->setExpectedException('\Tala\Exception\InvalidResponseException');
        $response = new Response('');
    }

    public function testInvalidXmlConstructor()
    {
        $this->setExpectedException('\Tala\Exception\InvalidResponseException');
        $response = new Response('sometext');
    }

    public function testDeclined()
    {
        $this->setExpectedException('\Tala\Exception', 'Transaction Declined');
        $this->getDeclinedResponse();
    }

    public function testMessage()
    {
        $response = $this->getSuccessResponse();
        $this->assertEquals('Transaction Approved', $response->getMessage());
    }

    public function testGatewayReference()
    {
        $response = $this->getSuccessResponse();
        $this->assertEquals('000000030884cdc6', $response->getGatewayReference());
    }
}
