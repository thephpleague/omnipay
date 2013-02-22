<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockResponse('PurchaseSuccess.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('130215141054377801316798', $response->getGatewayReference());
        $this->assertSame('AuthCode: 672167', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockResponse('PurchaseFailure.txt');
        $response = new Response($httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getGatewayReference());
        $this->assertSame('Input variable errors', $response->getMessage());
    }
}
