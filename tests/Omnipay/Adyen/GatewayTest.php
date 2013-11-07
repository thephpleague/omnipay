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
}
