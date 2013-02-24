<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Dummy\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new Response($this->getMockRequest(), array('reference' => 'abc123'));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getGatewayReference());
    }
}
