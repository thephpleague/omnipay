<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testTwoPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('TwoPurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testTwoPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('TwoPurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }
}
