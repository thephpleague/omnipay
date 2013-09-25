<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setSubAccountId('123456');
    }

    public function testFetchIssuers()
    {
        /** @var \Omnipay\TargetPay\Message\FetchIssuersRequest $request */
        $request = $this->gateway->fetchIssuers();

        $this->assertInstanceOf('Omnipay\TargetPay\Message\FetchIssuersRequest', $request);
        $this->assertNull($request->getData());
    }

    // Disabled because without setting a payment method, a request can't be created
    // Covered by other tests below
    public function testPurchase()
    {}

    // Disabled because without setting a payment method, a request can't be created
    // Covered by other tests below
    public function testPurchaseParameters()
    {}

    public function testPurchaseMrcash()
    {
        $this->gateway->setPaymentMethod('mrcash');

        /** @var \Omnipay\TargetPay\Message\MrcashPurchaseRequest $request */
        $request = $this->gateway->purchase(array(
            'amount' => '100.00',
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'language' => 'EN',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));

        $this->assertInstanceOf('Omnipay\TargetPay\Message\MrcashPurchaseRequest', $request);
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('EN', $request->getLanguage());
        $this->assertSame('http://localhost/return', $request->getReturnUrl());
        $this->assertSame('http://localhost/notify', $request->getNotifyUrl());
    }

    public function testPurchaseIdeal()
    {
        $this->gateway->setPaymentMethod('ideal');

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

    public function testPurchaseDirectebanking()
    {
        $this->gateway->setPaymentMethod('directebanking');

        /** @var \Omnipay\TargetPay\Message\DirectebankingPurchaseRequest $request */
        $request = $this->gateway->purchase(array(
            'amount' => '100.00',
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'country' => '00',
            'language' => 'EN',
            'serviceType' => '0',
            'returnUrl' => 'http://localhost/return',
            'notifyUrl' => 'http://localhost/notify',
        ));

        $this->assertInstanceOf('Omnipay\TargetPay\Message\DirectebankingPurchaseRequest', $request);
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('00', $request->getCountry());
        $this->assertSame('EN', $request->getLanguage());
        $this->assertSame('0', $request->getServiceType());
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
