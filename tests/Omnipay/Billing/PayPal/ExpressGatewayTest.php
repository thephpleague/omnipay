<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PayPal;

use Omnipay\GatewayTestCase;

class ExpressGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new ExpressGateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
        );
    }

    public function testAuthorize()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }

    public function testPurchase()
    {
        $this->setMockResponse($this->httpClient, 'ExpressPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('\Omnipay\RedirectResponse', $response);
        $this->assertEquals('https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=EC-5BV04722RH241693H', $response->getRedirectUrl());
    }
}
