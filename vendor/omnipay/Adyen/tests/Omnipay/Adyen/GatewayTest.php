<?php

namespace Omnipay\Adyen;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $gateway;

    public function __construct()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => 10.00));
        $parameters = $request->getParameters();
        $amount = $parameters['amount'];
        $this->assertInstanceOf('Omnipay\Adyen\Message\PurchaseRequest', $request);
        $this->assertSame(10.00, $amount);
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => 10.00));
        $parameters = $request->getParameters();
        $amount = $parameters['amount'];
        $this->assertInstanceOf('Omnipay\Adyen\Message\CompletePurchaseRequest', $request);
        $this->assertSame(10.00, $amount);
    }
    
    public function testGetSetSessionValidity()
    {
        $this->gateway->setSessionValidity('2013-11-05T11:27:59');
        $this->assertSame($this->gateway->getSessionValidity(), '2013-11-05T11:27:59');
    }
    
    public function testGetSetMerchantReference()
    {
        $this->gateway->setMerchantReference('TESTREF');
        $this->assertSame($this->gateway->getMerchantReference(), 'TESTREF');
    }
    
    public function testGetSetMerchantAccount()
    {
        $this->gateway->setMerchantAccount('TESTACC');
        $this->assertSame($this->gateway->getMerchantAccount(), 'TESTACC');
    }
    
    public function testGetSetSkinCode()
    {
        $this->gateway->setSkinCode('da45gy6');
        $this->assertSame($this->gateway->getSkinCode(), 'da45gy6');
    }
    
    public function testGetSetShipBeforeDate()
    {
        $this->gateway->setShipBeforeDate('2012-12-21');
        $this->assertSame($this->gateway->getShipBeforeDate(), '2012-12-21');
    }
    
    public function testGetSetSecret()
    {
        $this->gateway->setSecret('^hfyJs7f_K8');
        $this->assertSame($this->gateway->getSecret(), '^hfyJs7f_K8');
    }
    
    public function testGetSetShopperLocale()
    {
        $this->gateway->setShopperLocale('en_GB');
        $this->assertSame($this->gateway->getShopperLocale(), 'en_GB');
    }
    
    public function testGetSetAllowedMethods()
    {
        $this->gateway->setAllowedMethods('visa');
        $this->assertSame($this->gateway->getAllowedMethods(), 'visa');
    }
    
    public function testGetName()
    {
        $this->assertSame($this->gateway->getName(), 'Adyen');
    }
}
