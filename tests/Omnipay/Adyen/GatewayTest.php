<?php

namespace Omnipay\Adyen;

use Omnipay\GatewayTestCase;

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
        $this->gateway->setAmount(10);
        $request = $this->gateway->purchase();
        $this->assertInstanceOf('Omnipay\Adyen\Message\PurchaseRequest', $request);
        $this->assertSame(10, $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $this->gateway->setAmount(10);
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf('Omnipay\Adyen\Message\CompletePurchaseRequest', $request);
        $this->assertSame(10, $this->gateway->getAmount());
    }
}
