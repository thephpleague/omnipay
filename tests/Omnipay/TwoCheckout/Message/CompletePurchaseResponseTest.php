<?php

namespace Omnipay\TwoCheckout\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new CompletePurchaseresponse($this->getMockRequest(), array('order_number' => 'abc123'));

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
