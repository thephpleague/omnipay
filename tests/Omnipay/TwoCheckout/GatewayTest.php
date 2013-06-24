<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout;

use Omnipay\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccountNumber('123456');
        $this->gateway->setSecretWord('secret');

        $this->options = array(
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $source = new CreditCard;
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertContains('https://www.2checkout.com/checkout/purchase?', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseError()
    {
        $this->getHttpRequest()->request->replace(array('order_number' => '5', 'key' => 'ZZZ'));

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'order_number' => '5',
                'key' => md5('secret123456510.00'),
            )
        );

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('5', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
