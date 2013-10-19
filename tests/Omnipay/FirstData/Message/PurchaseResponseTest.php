<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\FirstData\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $response = new PurchaseResponse($this->getMockRequest(), array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        ));

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('POST', $response->getRedirectMethod());
    }
}
