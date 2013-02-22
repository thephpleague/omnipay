<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('CompletePurchaseSuccess.txt');
        $response = new CompletePurchaseResponse($httpResponse->json());

        $this->assertTrue($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('CompletePurchaseFailure.txt');
        $response = new CompletePurchaseResponse($httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getGatewayReference());
        $this->assertSame('The resource cannot be confirmed', $response->getMessage());
    }
}
