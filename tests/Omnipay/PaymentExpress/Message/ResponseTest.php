<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('PxPostPurchaseSuccess.txt');
        $response = new Response($httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getGatewayReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('PxPostPurchaseFailure.txt');
        $response = new Response($httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('PxPayCompletePurchaseSuccess.txt');
        $response = new Response($httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getGatewayReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('PxPayCompletePurchaseFailure.txt');
        $response = new Response($httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('Length of the data to decrypt is invalid.', $response->getMessage());
    }
}
