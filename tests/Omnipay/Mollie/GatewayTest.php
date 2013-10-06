<?php

namespace Omnipay\Mollie;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers(array('partnerId' => 'abc123'));

        $this->assertInstanceOf('Omnipay\Mollie\Message\FetchIssuersRequest', $request);
        $this->assertSame('abc123', $request->getPartnerId());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Mollie\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Mollie\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
