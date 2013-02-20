<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use Mockery as m;
use Omnipay\TestCase;

class AbstractGatewayTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock("\Omnipay\Common\AbstractGateway[getName,defineSettings,send]");
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testAuthorize()
    {
        $this->gateway->authorize(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testCompleteAuthorize()
    {
        $this->gateway->completeAuthorize(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testCapture()
    {
        $this->gateway->capture(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testPurchase()
    {
        $this->gateway->purchase(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testCompletePurchase()
    {
        $this->gateway->completePurchase(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testRefund()
    {
        $this->gateway->refund(array());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\BadMethodCallException
     */
    public function testVoid()
    {
        $this->gateway->void(array());
    }

    public function testGetShortName()
    {
        // test a couple of known getShortName() examples
        $gateway = GatewayFactory::create('PayPal_Express');
        $this->assertSame('PayPal_Express', $gateway->getShortName());

        $gateway = GatewayFactory::create('Stripe');
        $this->assertSame('Stripe', $gateway->getShortName());
    }
}
