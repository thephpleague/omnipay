<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'amount' => '10.00',
            'transactionId' => '608845'
        ));
    }

    public function testEndpoint()
    {
        $this->assertSame('https://gateway.agms.com/roxapi/agms.asmx', $this->request->getEndpoint());
    }
    
    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('10.00', $data['Amount']);
        $this->assertSame('608845', $data['TransactionID']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulCapture.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('Capture successful: Approved', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FailedCapture.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550949', $response->getTransactionReference());
        $this->assertSame('Transaction ID is required when performing a capture.  ', $response->getMessage());
    }
}
