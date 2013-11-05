<?php

namespace Omnipay\Adyen\Message;

require_once 'PHPUnit/Autoload.php';
$autoloader = require __DIR__.'/../../../../../../autoload.php';
$autoloader->add('Omnipay', __DIR__ . '/../../../');

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
            'paymentAmount' => '10',
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

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetDataInvalidSignature()
    {
        $this->getHttpRequest()->request->set('merchantSig', 'zzz234aa');

        $this->request->getData();
    }

    public function testGenerateResponseSignature()
    {
        $this->request->initialize(array(
            'merchantAccount' => 'BidZoneNL',
            'merchantReference' => 'TEST-10000',
            'secret' => 'test',
            'skinCode' => '05cp1ZtM',
            'paymentAmount' => '10',
            'currencyCode' => 'EUR',
            'testMode' => true,
            'shipBeforeDate' => '2013-11-11',
            'sessionValidity' => '2013-11-05T11:27:59'
        ));

        $this->assertSame('K9Ix8bSnBhlt3GKs/vOQtjFT9mY==', $this->request->generateResponseSignature());
    }

    public function testSendSuccess()
    {
        $this->getHttpRequest()->request->set('authResult', 'AUTHORISED');
        $this->getHttpRequest()->request->set('merchantSig', $this->request->generateResponseSignature());
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('TEST-10000', $response->getMerchantReference());
        $this->assertSame('AUTHORISED', 'AUTHORISED');
    }

    public function testSendError()
    {
        $this->getHttpRequest()->request->set('authResult', 'REFUSED');
        $this->getHttpRequest()->request->set('merchantSig', $this->request->generateResponseSignature());
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('TEST-10000', $response->getMerchantReference());
        $this->assertSame('REFUSED', 'REFUSED');
    }
}
