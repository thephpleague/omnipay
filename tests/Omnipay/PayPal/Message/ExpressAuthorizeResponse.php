<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

use Omnipay\TestCase;

class ExpressAuthorizeResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new ExpressAuthorizeResponse('example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testExpressPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('ExpressPurchaseSuccess.txt');
        $response = new ExpressAuthorizeResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('EC-42721413K79637829', $response->getExpressRedirectToken());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testExpressPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('ExpressPurchaseFailure.txt');
        $response = new ExpressAuthorizeResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getExpressRedirectToken());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('This transaction cannot be processed. The amount to be charged is zero.', $response->getMessage());
    }
}
