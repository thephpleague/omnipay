<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PaymentExpress;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testEmptyConstructor()
    {
        $response = new Response('');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage String could not be parsed as XML
     */
    public function testInvalidXmlConstructor()
    {
        $response = new Response('sometext');
    }

    public function testConstructDeclined()
    {
        $response = new Response('<Txn><HelpText>Transaction Declined</HelpText><Success>0</Success></Txn>');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Transaction Declined', $response->getMessage());
    }

    public function testConstructSuccess()
    {
        $response = new Response('<Txn><ReCo>00</ReCo><ResponseText>APPROVED</ResponseText><HelpText>Transaction Approved</HelpText><Success>1</Success><DpsTxnRef>000000030884cdc6</DpsTxnRef><TxnRef>inv1278</TxnRef></Txn>');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('000000030884cdc6', $response->getGatewayReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }
}
