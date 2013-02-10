<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\TwoCheckout;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Tala\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        $response = new Response('');
    }

    public function testConstruct()
    {
        $response = new Response('abc123');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getGatewayReference());
    }
}
