<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Payflow;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response('');
    }

    public function testConstructError()
    {
        $response = new Response('RESULT=1&RESPMSG=User authentication failed');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('User authentication failed', $response->getMessage());
    }

    public function testConstructSuccess()
    {
        $response = new Response('RESULT=0&PNREF=V19R3EF62FBE&RESPMSG=Approved&AUTHCODE=048747&CVV2MATCH=Y');

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('V19R3EF62FBE', $response->getGatewayReference());
        $this->assertEquals('Approved', $response->getMessage());
    }
}
