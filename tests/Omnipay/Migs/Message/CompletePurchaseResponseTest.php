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

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), 'vpc_Message=Approved&vpc_ReceiptNo=12345&vpc_TxnResponseCode=0');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), 'vpc_Message=Error&vpc_ReceiptNo=12345&vpc_TxnResponseCode=1');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
        $this->assertNotSame('Approved', $response->getMessage());
    }
}
