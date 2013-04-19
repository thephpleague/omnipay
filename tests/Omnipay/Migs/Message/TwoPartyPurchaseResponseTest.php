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

class TwoPartyPurchaseResponseTest extends TestCase
{
    public function testTwoPartyPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('TwoPartyPurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testTwoPartyPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('TwoPartyPurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertInstanceOf('Omnipay\Migs\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
        $this->assertNull($response->getCode());
    }
}
