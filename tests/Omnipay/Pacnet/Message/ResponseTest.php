<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new Response($this->getMockRequest(), 'example=value&foo=bar');
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }

    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165646', $response->getTransactionReference());
        $this->assertEquals('Invalid because activity on the account is blocked.', $response->getMessage());
        $this->assertEquals('rejected:AccountBlocked', $response->getCode());
    }

    public function testVoidSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('VoidSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testVoidFailure()
    {
        try {
            $httpResponse = $this->getMockHttpResponse('VoidFailure.txt');
            $response = new Response($this->getMockRequest(), $httpResponse->getBody());
        } catch (Guzzle\Http\Exception\ClientErrorResponseException $e) {
            $this->assetEquals('404', $e->getResponse()->getStatusCode());
            $this->assertEquals('Not Found', $e->getResponse()->getReasonPhrase());
        }
    }

    public function testRefundSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('RefundSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testRefundFailure()
    {
        $httpResponse = $this->getMockHttpResponse('RefundFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165604', $response->getTransactionReference());
        $this->assertEquals('Invalid because the original payment is not a settled debit.', $response->getMessage());
        $this->assertEquals('invalid:OriginalPaymentNotSettled', $response->getCode());
    }

    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165810', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function testAuthorizeFailure()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165844', $response->getTransactionReference());
        $this->assertEquals('Invalid because activity on the account is blocked.', $response->getMessage());
        $this->assertEquals('rejected:AccountBlocked', $response->getCode());
    }

    public function captureSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165927', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
    }

    public function captureFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CaptureFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('10000165935', $response->getTransactionReference());
        $this->assertEquals('Invalid because the preauthorization #10000****19 was already used', $response->getMessage());
        $this->assertEquals('rejected:PreauthAlreadyUsed', $response->getCode());
    }
}
