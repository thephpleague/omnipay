<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('PurchaseSuccess.txt');
        $response = new Response($httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('f3d94dd5c0f743a788fc943402757c58', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('PurchaseFailure.txt');
        $response = new Response($httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame("Missing parameter: 'Order Number'", $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('CompletePurchaseSuccess.txt');
        $response = new Response($httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('8a88d40cab5b47fab25e24d6228180a7', $response->getGatewayReference());
        $this->assertSame('OK', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('CompletePurchaseFailure.txt');
        $response = new Response($httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Unable to find transaction', $response->getMessage());
    }
}
