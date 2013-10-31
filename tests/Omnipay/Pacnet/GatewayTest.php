<?php

namespace Omnipay\Pacnet;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setUserName('ernest');
        $this->gateway->setPRN('840033');
        $this->gateway->setSharedSecret('all good men die young');

        $this->options = array(
            'amount'    => '10.00',
            'currency'  => 'USD',
            'card'      => array(
                'number'        => '4000000000000010',
                'expiryMonth'   => '09',
                'expiryYear'    => '2019',
                'cvv'           => '123'
            )
        );
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Pacnet\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Pacnet\Message\RefundRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testVoid()
    {
        $request = $this->gateway->void();
        $request->setTransactionReference('10000160381');

        $this->assertInstanceOf('Omnipay\Pacnet\Message\VoidRequest', $request);
        $this->assertSame('10000160381', $request->getTransactionReference());
    }
}
