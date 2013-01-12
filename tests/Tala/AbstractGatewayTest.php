<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = $this->getMockForAbstractClass('\Tala\AbstractGateway');
        $this->card = new CreditCard();
        $this->request = new Request();
    }

    public function testGetDefaultSettings()
    {
        $this->assertEquals(array(), $this->gateway->getDefaultSettings());
    }

    public function testHttpClient()
    {
        $this->assertInstanceOf('\Tala\HttpClient\HttpClientInterface', $this->gateway->getHttpClient());
    }

    public function testSetHttpClient()
    {
        $this->gateway->setHttpClient('fakeHttpClient');
        $this->assertEquals('fakeHttpClient', $this->gateway->getHttpClient());
    }

    public function testHttpRequest()
    {
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Request', $this->gateway->getHttpRequest());
    }

    public function testSetHttpRequest()
    {
        $this->gateway->setHttpRequest('fakeHttpRequest');
        $this->assertEquals('fakeHttpRequest', $this->gateway->getHttpRequest());
    }

    public function testAuthorize()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->authorize($this->request, $this->card);
    }

    public function testCompleteAuthorize()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->completeAuthorize($this->request);
    }

    public function testCapture()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->capture($this->request);
    }

    public function testPurchase()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->purchase($this->request, $this->card);
    }

    public function testCompletePurchase()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->completePurchase($this->request);
    }

    public function testRefund()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->refund($this->request);
    }

    public function testVoid()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->gateway->void($this->request);
    }
}
