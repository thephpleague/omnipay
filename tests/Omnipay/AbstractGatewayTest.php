<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

use Mockery as m;

class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock("\Omnipay\AbstractGateway[getName,defineSettings]");
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testAuthorize()
    {
        $this->gateway->authorize(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testCompleteAuthorize()
    {
        $this->gateway->completeAuthorize(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testCapture()
    {
        $this->gateway->capture(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testPurchase()
    {
        $this->gateway->purchase(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testCompletePurchase()
    {
        $this->gateway->completePurchase(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
     */
    public function testRefund()
    {
        $this->gateway->refund(array());
    }

    /**
     * @expectedException \Omnipay\Exception\UnsupportedMethodException
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

    public function testHttpClient()
    {
        $mockHttpClient = m::mock('\Omnipay\HttpClient\HttpClientInterface');

        $this->gateway->setHttpClient($mockHttpClient);
        $this->assertSame($mockHttpClient, $this->gateway->getHttpClient());
    }

    public function testHttpRequest()
    {
        $mockHttpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway->setHttpRequest($mockHttpRequest);
        $this->assertSame($mockHttpRequest, $this->gateway->getHttpRequest());
    }
}
