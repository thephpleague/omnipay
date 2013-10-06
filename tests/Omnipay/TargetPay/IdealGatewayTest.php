<?php

namespace Omnipay\TargetPay;

use Omnipay\GatewayTestCase;

class IdealGatewayTest extends GatewayTestCase
{
    /**
     * @var IdealGateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new IdealGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setSubAccountId('123456');
    }

    public function testFetchIssuers()
    {
        /** @var \Omnipay\TargetPay\Message\FetchIssuersRequest $request */
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf('Omnipay\TargetPay\Message\FetchIssuersRequest', $request);
        $this->assertNull($request->getData());
    }

    public function testPurchase()
    {
        /** @var \Omnipay\TargetPay\Message\IdealPurchaseRequest $request */
        $request = $this->gateway->purchase(array(
            'issuer' => '0001',
            'amount' => '100.00',
            'currency' => 'EUR',
            'description' => 'desc',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));

        $this->assertInstanceOf('Omnipay\TargetPay\Message\IdealPurchaseRequest', $request);
        $this->assertSame('0001', $request->getIssuer());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('EN', $request->getLanguage());
        $this->assertSame('http://localhost/return', $request->getReturnUrl());
        $this->assertSame('http://localhost/notify', $request->getNotifyUrl());
    }

    public function testCompletePurchase()
    {
        /** @var \Omnipay\TargetPay\Message\CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase(array(
            'transactionId' => '123456',
            'exchangeOnce' => true,
        ));

        $this->assertInstanceOf('Omnipay\TargetPay\Message\CompletePurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertTrue($request->getExchangeOnce());
    }
}
