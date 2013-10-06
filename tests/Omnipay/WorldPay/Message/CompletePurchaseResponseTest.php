<?php

namespace Omnipay\WorldPay\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new CompletePurchaseresponse(
            $this->getMockRequest(),
            array(
                'transStatus' => 'Y',
                'transId' => 'abc123',
                'rawAuthMessage' => 'Success Message'
            )
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('Success Message', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $response = new CompletePurchaseresponse(
            $this->getMockRequest(),
            array(
                'transStatus' => 'N',
                'transId' => null,
                'rawAuthMessage' => 'Declined'
            )
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testCompletePurchaseInvalid()
    {
        $response = new CompletePurchaseresponse($this->getMockRequest(), array());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
