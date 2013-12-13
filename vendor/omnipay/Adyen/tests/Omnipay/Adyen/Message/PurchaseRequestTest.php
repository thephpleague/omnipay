<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Tests\TestCase;

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
    
    public function testGetSetSessionValidity()
    {
        $this->request->setSessionValidity('2013-11-05T11:27:59');
        $this->assertSame($this->request->getSessionValidity(), '2013-11-05T11:27:59');
    }
    
    public function testGetSetMerchantReference()
    {
        $this->request->setMerchantReference('TESTREF');
        $this->assertSame($this->request->getMerchantReference(), 'TESTREF');
    }
    
    public function testGetSetMerchantAccount()
    {
        $this->request->setMerchantAccount('TESTACC');
        $this->assertSame($this->request->getMerchantAccount(), 'TESTACC');
    }
    
    public function testGetSetSkinCode()
    {
        $this->request->setSkinCode('da45gy6');
        $this->assertSame($this->request->getSkinCode(), 'da45gy6');
    }
    
    public function testGetSetShipBeforeDate()
    {
        $this->request->setShipBeforeDate('2012-12-21');
        $this->assertSame($this->request->getShipBeforeDate(), '2012-12-21');
    }
    
    public function testGetSetSecret()
    {
        $this->request->setSecret('^hfyJs7f_K8');
        $this->assertSame($this->request->getSecret(), '^hfyJs7f_K8');
    }
    
    public function testGetSetShopperLocale()
    {
        $this->request->setShopperLocale('en_GB');
        $this->assertSame($this->request->getShopperLocale(), 'en_GB');
    }
    
    public function testGetSetAllowedMethods()
    {
        $this->request->setAllowedMethods('visa');
        $this->assertSame($this->request->getAllowedMethods(), 'visa');
    }
    
    public function testGetEndpoint()
    {
        $this->assertSame($this->request->getEndpoint(), 'https://test.adyen.com/hpp/pay.shtml');
    }
}
