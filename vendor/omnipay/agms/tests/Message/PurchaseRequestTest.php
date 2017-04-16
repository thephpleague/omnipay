<?php

namespace Omnipay\Agms\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
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
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('SuccessfulPurchase.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549865', $response->getTransactionReference());
        $this->assertSame('Approved', $response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FailedPurchase.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('549879', $response->getTransactionReference());
        $this->assertSame('Declined', $response->getMessage());
    }
}
