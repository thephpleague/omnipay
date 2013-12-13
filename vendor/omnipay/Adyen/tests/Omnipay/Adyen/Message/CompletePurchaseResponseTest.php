<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

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
    
    public function testIsSuccessful()
    {
        $response = new CompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'success' => true,
            )
        );
        
        $this->assertTrue($response->isSuccessful());
    }
    
    public function testGetResponse()
    {
        
        $mock = $this->getMockRequest();
        $response = new CompletePurchaseResponse($mock, array());
        $this->assertSame(serialize($response), serialize($response->getResponse()));
        
    }
}
