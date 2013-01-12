<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Payflow;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response('');
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage User authentication failed
     */
    public function testConstructError()
    {
        $response = new Response('RESULT=1&RESPMSG=User authentication failed');
    }

    public function testConstructSuccess()
    {
        $response = new Response('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
        $this->assertEquals('Approved', $response->getMessage());
    }
}
