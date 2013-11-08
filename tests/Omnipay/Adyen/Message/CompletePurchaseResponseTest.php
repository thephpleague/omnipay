<?php

namespace Omnipay\Adyen\Message;

use Omnipay\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
            )
        );
        
        $this->assertTrue($response->isSuccessful());
    }
    
    public function testCompletePurchaseFailure()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => false,
            )
        );
        
        $this->assertFalse($response->isSuccessful());
    }
}
