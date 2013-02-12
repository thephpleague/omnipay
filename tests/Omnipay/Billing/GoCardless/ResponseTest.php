<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\GoCardless;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testConstructEmptyData()
    {
        $response = new Response('', 'abc');
    }
    /**
     * @expectedException Omnipay\Exception\InvalidResponseException
     */
    public function testConstructEmptyReference()
    {
        $response = new Response('{"success":true}', '');
    }

    public function testConstructSuccess()
    {
        $response = new Response('{"success":true}', 'abc');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testConstructError()
    {
        $response = new Response('{"error":["The resource cannot be confirmed"]}', 'abc');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('abc', $response->getGatewayReference());
        $this->assertSame('The resource cannot be confirmed', $response->getMessage());
    }
}
