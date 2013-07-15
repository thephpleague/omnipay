<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay;

use Omnipay\Common\CreditCard;
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
        $this->gateway->setMerchantId('111111');
        $this->gateway->setSecretCode('222222');

        $this->options = array(
            'transactionId' => '123456',
            'amount' => '100.00',
            'currency' => 'EUR',
            'paymentMethod' => 'IDEAL',
            'issuer' => 'ABNAMRO',
            'card' => array(
                'email' => 'something@example.com'
            ),
        );
    }

    public function testPurchase()
    {
        /** @var \Omnipay\Icepay\Message\PurchaseRequest $request */
        $request = $this->gateway->purchase($this->options);

        /** @var CreditCard $card */
        $card = $request->getCard();

        $this->assertInstanceOf('Omnipay\Icepay\Message\PurchaseRequest', $request);
        $this->assertSame('123456', $request->getTransactionId());
        $this->assertSame('100.00', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
        $this->assertSame('IDEAL', $request->getPaymentMethod());
        $this->assertSame('ABNAMRO', $request->getIssuer());
        $this->assertSame('something@example.com', $card->getEmail());
    }
}
