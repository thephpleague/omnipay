<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
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
        $this->assertSame('608845', $data['TransactionID']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulVoid.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550946', $response->getTransactionReference());
        $this->assertSame('void successful: Approved', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FailedVoid.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('550953', $response->getTransactionReference());
        $this->assertSame('Transaction ID is required when performing a void or refund.  ', $response->getMessage());
    }
}
