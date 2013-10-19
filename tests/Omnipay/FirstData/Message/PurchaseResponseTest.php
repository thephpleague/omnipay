<?php

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
