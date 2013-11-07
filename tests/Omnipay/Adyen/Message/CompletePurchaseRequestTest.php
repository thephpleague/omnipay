<?php

namespace Omnipay\Adyen\Message;

use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantAccount' => 'BidZoneNL',
            'merchantReference' => 'TEST-10000',
            'secret' => 'test',
            'skinCode' => '05cp1ZtM',
            'amount' => 10.00,
            'currencyCode' => 'EUR',
            'testMode' => true,
            'shipBeforeDate' => '2013-11-11',
            'sessionValidity' => '2013-11-05T11:27:59'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame($this->getHttpRequest()->request->all(), $data);
    }

    public function testGenerateResponseSignature()
    {
        $this->request->initialize(array(
            'merchantAccount' => 'BidZoneNL',
            'merchantReference' => 'TEST-10000',
            'secret' => 'test',
            'skinCode' => '05cp1ZtM',
            'amount' => 10.00,
            'currencyCode' => 'EUR',
            'testMode' => true,
            'shipBeforeDate' => '2013-11-11',
            'sessionValidity' => '2013-11-05T11:27:59'
        ));
        $this->assertSame('9c4u9SHR0eP7+pX2D2maZVgFqSQ=', $this->request->generateResponseSignature());
    }

    public function testSendSuccess()
    {
        $this->getHttpRequest()->request->set('authResult', 'AUTHORISED');
        $this->getHttpRequest()->request->set('merchantSig', $this->request->generateResponseSignature());
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testSendError()
    {
        $this->getHttpRequest()->request->set('authResult', 'REFUSED');
        $this->getHttpRequest()->request->set('merchantSig', $this->request->generateResponseSignature());
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
    }
}
