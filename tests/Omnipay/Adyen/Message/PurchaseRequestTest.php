<?php

namespace Omnipay\Adyen\Message;

use Omnipay\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'merchantAccount' => 'testacc',
            'merchantReference' => 'TEST-10000',
            'secret' => 'test',
            'skinCode' => '05cp1ZtM',
            'amount' => 10.00,
            'testMode' => true,
            'shipBeforeDate' => '2013-11-11',
            'sessionValidity' => '2013-11-05T11:27:59',
            'currency' => 'EUR'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('testacc', $data['merchantAccount']);
        $this->assertSame(1000, $data['paymentAmount']);
        $this->assertSame('EUR', $data['currencyCode']);
        $this->assertSame('TEST-10000', $data['merchantReference']);
        $this->assertSame('uqFsJWd5nYfGH9x4JSwyNuApZCU=', $data['merchantSig']);
    }

    public function testGenerateSignature()
    {
        $this->assertSame('uqFsJWd5nYfGH9x4JSwyNuApZCU=', $this->request->generateSignature($this->request->getData()));
    }
}
