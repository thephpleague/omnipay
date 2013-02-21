<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Ben Swinburne <ben.swinburne@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecureTrading;

use SimpleXmlElement;
use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new Response(new SimpleXmlElement('<Response><TransactionOutputData CrossReference="abc123" /></Response>'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc123', $response->getGatewayReference());
        $this->assertSame('', $response->getMessage());
    }

    public function testConstructError()
    {
        $response = new Response(new SimpleXmlElement('<CardDetailsTransactionResponse xmlns="https://www.thepaymentgateway.net/"><CardDetailsTransactionResult AuthorisationAttempted="False"><StatusCode>30</StatusCode><Message>Input variable errors</Message><ErrorMessages><MessageDetail><Detail>Required variable (PaymentMessage.TransactionDetails.OrderID) is missing</Detail></MessageDetail></ErrorMessages></CardDetailsTransactionResult><TransactionOutputData /></CardDetailsTransactionResponse>'));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getGatewayReference());
        $this->assertSame('Input variable errors', $response->getMessage());
    }
}
