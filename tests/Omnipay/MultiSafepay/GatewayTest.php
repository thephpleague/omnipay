<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    protected function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccountId('111111');
        $this->gateway->setSiteId('222222');
        $this->gateway->setSiteCode('333333');

        $this->options = array(
            'transactionId' => '123456',
            'currency' => 'EUR',
            'amount' => '100.00',
            'description' => 'desc',
            'clientIp' => '127.0.0.1',
            'card' => array(
                'email' => 'something@example.com',
            )
        );
    }

    public function testPurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\PurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $request->getCard()->getEmail());
    }

    public function testCompletePurchase()
    {
        /** @var \Omnipay\MultiSafepay\Message\CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase($this->options);

        $this->assertInstanceOf('Omnipay\MultiSafepay\Message\CompletePurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('desc', $request->getDescription());
        $this->assertSame('127.0.0.1', $request->getClientIp());
        $this->assertSame('something@example.com', $request->getCard()->getEmail());
    }
}
