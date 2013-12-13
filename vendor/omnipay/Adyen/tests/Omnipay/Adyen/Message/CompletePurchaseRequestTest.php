<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantAccount' => 'testacc',
            'merchantReference' => 'TEST-10000',
            'secret' => 'test',
            'skinCode' => '05cp1ZtM',
            'amount' => 10.00,
            'currency' => 'EUR',
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
        $this->assertSame(
            'Ti+ACycv7TmV3VY6hfQ6tIIdhGM=',
            $this->request->generateResponseSignature($this->request->getData())
        );
    }
    
    public function testSend()
    {
        $this->getHttpRequest()->request->set('authResult', 'AUTHORISED');
        $authResult = $this->request->getData();
        $this->assertSame('AUTHORISED', $authResult['authResult']);
        
    }
}
