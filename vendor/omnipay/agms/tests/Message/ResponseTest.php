<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testSuccessFulAuthorize()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulAuthorize.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550945', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testFailedAuthorize()
    {
        $httpResponse = $this->getMockHttpResponse('FailedAuthorize.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550941', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testSuccessfulPurchase()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulPurchase.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549865', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testFailedPurchase()
    {
        $httpResponse = $this->getMockHttpResponse('FailedPurchase.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549879', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }

    public function testSuccessfulCapture()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulCapture.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('Capture successful: Approved', $response->getMessage());
    }

    public function testPartialCapture()
    {
        $httpResponse = $this->getMockHttpResponse('PartialCapture.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('Capture successful: Approved', $response->getMessage());
    }

    public function testFailedCapture()
    {
        $httpResponse = $this->getMockHttpResponse('FailedCapture.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550949', $response->getTransactionReference());
        $this->assertSame('Transaction ID is required when performing a capture.  ', $response->getMessage());
    }

    public function testSuccessfulRefund()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulRefund.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('refund successful: Approved', $response->getMessage());
    }

    public function testPartialRefund()
    {
        $httpResponse = $this->getMockHttpResponse('PartialRefund.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('refund successful: Approved', $response->getMessage());
    }

    public function testFailedRefund()
    {
        $httpResponse = $this->getMockHttpResponse('FailedRefund.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550953', $response->getTransactionReference());
        $this->assertSame('Transaction ID is required when performing a void or refund.  ', $response->getMessage());
    }

    public function testSuccessfulVoid()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulVoid.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('void successful: Approved', $response->getMessage());
    }

    public function testFailedVoid()
    {
        $httpResponse = $this->getMockHttpResponse('FailedVoid.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550953', $response->getTransactionReference());
        $this->assertSame('Transaction ID is required when performing a void or refund.  ', $response->getMessage());
    }

    public function testCreateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulCreateCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'AddToSAFE');
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1032628', $response->getCardReference());
        $this->assertSame('SAFE Record added successfully. No transaction processed.', $response->getMessage());
    }

    public function testCreateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FailedCreateCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'AddToSAFE');
        
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Add to safe failed: Missing \'credit card\' info.', $response->getMessage());
    }

    public function testUpdateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulUpdateCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'UpdateSAFE');
        
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1032634', $response->getCardReference());
        $this->assertSame('SAFE Record updated successfully. No transaction processed.', $response->getMessage());
    }

    public function testUpdateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FailedUpdateCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'UpdateSAFE');

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Update safe failed. No SAFE ID given.', $response->getMessage());
    }

    public function testDeleteCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('SuccessfulDeleteCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'DeleteFromSAFE');

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('SAFE record has been deactivated', $response->getMessage());
    }

    public function testDeleteCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FailedDeleteCard.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody(), 'DeleteFromSAFE');

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Delete from safe failed. No SAFE ID given.', $response->getMessage());
    }

    public function testInvalidLogin()
    {
        $httpResponse = $this->getMockHttpResponse('InvalidLogin.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Authentication Failed', $response->getMessage());
    }
}
