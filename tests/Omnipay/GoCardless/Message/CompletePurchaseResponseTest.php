<?php

namespace Omnipay\GoCardless\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = new CompletePurchaseResponse($this->getMockRequest(), $httpResponse->json(), 'abc123');

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseFailure.txt');
        $response = new CompletePurchaseResponse($this->getMockRequest(), $httpResponse->json(), 'abc123');

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('The resource cannot be confirmed', $response->getMessage());
    }
}
