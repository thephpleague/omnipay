<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay;

use Omnipay\GatewayTestCase;

class DirectPostGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new DirectPostGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('abc123');
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\SecurePay\Message\DirectPostAuthorizeRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCompleteAuthorize()
    {
        $request = $this->gateway->completeAuthorize(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\SecurePay\Message\DirectPostCompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\SecurePay\Message\DirectPostPurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\SecurePay\Message\DirectPostCompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
