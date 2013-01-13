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

        $this->assertEquals('abc123', $response->getGatewayReference());
    }

    /**
     * @expectedException \Tala\Exception
     * @expectedExceptionMessage FAILURE
     */
    public function testConstructError()
    {
        $response = new Response(new SimpleXmlElement('<Response><ResponseCode>FAILURE</ResponseCode></Response>'));
    }
}
