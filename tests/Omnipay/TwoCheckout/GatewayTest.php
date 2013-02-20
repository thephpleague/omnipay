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

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->gateway->setUsername('abc');
        $this->gateway->setPassword('123');

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchase()
    {
        $source = new CreditCard;
        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\Common\Message\RedirectResponse', $response);
        $this->assertContains('https://www.2checkout.com/checkout/purchase?', $response->getRedirectUrl());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseError()
    {
        $this->httpRequest->request->replace(array('order_number' => '5', 'key' => 'ZZZ'));

        $response = $this->gateway->completePurchase($this->options);
    }

    public function testCompletePurchaseSuccess()
    {
        $this->httpRequest->request->replace(
            array(
                'order_number' => '5',
                'key' => md5('123abc510.00'),
            )
        );

        $response = $this->gateway->completePurchase($this->options);

        $this->assertTrue($response->isSuccessful());
    }
}
