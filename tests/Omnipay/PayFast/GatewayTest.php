<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayFast;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => 1200));

        $this->assertInstanceOf('\Omnipay\PayFast\Message\PurchaseRequest', $request);
        $this->assertSame(1200, $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase(array('amount' => 1200));

        $this->assertInstanceOf('\Omnipay\PayFast\Message\CompletePurchaseRequest', $request);
        $this->assertSame(1200, $request->getAmount());
    }
}
