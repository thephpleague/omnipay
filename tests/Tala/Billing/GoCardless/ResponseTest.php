<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\GoCardless;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response('', '');
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage The resource cannot be confirmed
     */
    public function testConstructError()
    {
        $response = new Response('{"error":["The resource cannot be confirmed"]}', 'abc');
    }

    public function testConstructSuccess()
    {
        $response = new Response('{"success":true}', 'abc');

        $this->assertEquals('abc', $response->getGatewayReference());
    }
}
