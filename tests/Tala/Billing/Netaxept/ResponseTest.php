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

use SimpleXmlElement;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructSuccess()
    {
        $response = new Response(new SimpleXmlElement('<Response><ResponseCode>OK</ResponseCode><TransactionId>abc123</TransactionId></Response>'));

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc123', $response->getGatewayReference());
        $this->assertSame('OK', $response->getMessage());
    }

    public function testConstructFailure()
    {
        $response = new Response(new SimpleXmlElement('<Response><ResponseCode>FAILURE</ResponseCode></Response>'));

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('FAILURE', $response->getMessage());
    }

    public function testConstructError()
    {
        $response = new Response(new SimpleXmlElement('<Response><Error><Message>Authentication Error</Message></Error></Response>'));

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Authentication Error', $response->getMessage());
    }
}
