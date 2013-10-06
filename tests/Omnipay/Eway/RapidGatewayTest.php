<?php

namespace Omnipay\Eway;

use Omnipay\GatewayTestCase;

class RapidGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new RapidGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Eway\Message\RapidPurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Eway\Message\RapidCompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
