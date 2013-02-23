<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('PurchaseSuccess.txt');
        $response = new Response($httpResponse->json());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('ch_1IU9gcUiNASROd', $response->getGatewayReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('PurchaseFailure.txt');
        $response = new Response($httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Your card was declined', $response->getMessage());
        $this->assertSame('ch_1IUAZQWFYrPooM', $response->getGatewayReference());
    }
}
