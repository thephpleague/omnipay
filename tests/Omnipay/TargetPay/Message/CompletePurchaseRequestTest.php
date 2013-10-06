<?php

namespace Omnipay\TargetPay\Message;

use Mockery as m;
use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $arguments = array($this->getHttpClient(), $this->getHttpRequest());
        $this->request = m::mock('Omnipay\TargetPay\Message\CompletePurchaseRequest[getEndpoint]', $arguments);
        $this->request->shouldReceive('getEndpoint')->andReturn('http://localhost');
        $this->request->setTransactionId('123456');
    }

    public function testData()
    {
        $data = $this->request->getData();

        $this->assertArrayHasKey('rtlo', $data);
        $this->assertSame('123456', $data['trxid']);
        $this->assertArrayHasKey('once', $data);
        $this->assertArrayHasKey('test', $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Transaction was cancelled', $response->getMessage());
        $this->assertEquals('TP0013', $response->getCode());
    }
}
