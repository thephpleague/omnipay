<?php

namespace Omnipay\TwoCheckout\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new Purchaseresponse($this->getMockRequest(), array('sid' => '12345', 'total' => '10.00'));

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://www.2checkout.com/checkout/purchase?sid=12345&total=10.00', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
    }
}
