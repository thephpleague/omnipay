<?php

namespace Omnipay\Pacnet;

use Omnipay\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setUsername('ernest');
        $this->gateway->setPaymentRoutingNumber('840033');
        $this->gateway->setSharedSecret('all good men die young');

        $this->options = array(
            'amount'    => '10.00',
            'currency'  => 'USD'
        );
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\Pacnet\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund($this->options);

        $this->assertInstanceOf('Omnipay\Pacnet\Message\RefundRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    public function testVoid()
    {
        $request = $this->gateway->void();
        $request->setTransactionReference('10000160381');

        $this->assertInstanceOf('Omnipay\Pacnet\Message\VoidRequest', $request);
        $this->assertSame('10000160381', $request->getTransactionReference());
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('Omnipay\Pacnet\Message\AuthorizeRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }

    public function testCapture()
    {
        $request = $this->gateway->capture($this->options);
        $request->setTransactionReference('10000165919');

        $this->assertInstanceOf('Omnipay\Pacnet\Message\CaptureRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
        $this->assertSame('10000165919', $request->getTransactionReference());
    }
}
