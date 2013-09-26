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

class DirectebankingGatewayTest extends GatewayTestCase
{
    /**
     * @var DirectebankingGateway
     */
    protected $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new DirectebankingGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setSubAccountId('123456');
    }

    public function testPurchase()
    {
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
