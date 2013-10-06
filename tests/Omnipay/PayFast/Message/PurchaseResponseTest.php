<?php

namespace Omnipay\PayFast\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $data = array('test' => '123');
        $response = new PurchaseResponse($this->getMockRequest(), $data, 'https://example.com/');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame($data, $response->getData());

        $this->assertSame('https://example.com/', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame($data, $response->getRedirectData());
    }
}
