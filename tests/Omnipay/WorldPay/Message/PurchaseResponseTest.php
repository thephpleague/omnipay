<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $response = new PurchaseResponse($this->getMockRequest(), array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        ));

        $this->getMockRequest()->shouldReceive('getEndpoint')->once()->andReturn('https://secure.worldpay.com/wcc/purchase');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://secure.worldpay.com/wcc/purchase?amount=1000&returnUrl=https%3A%2F%2Fwww.example.com%2Freturn', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
    }
}
