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

use Mockery as m;

class AbstractGatewayTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock("\Tala\AbstractGateway[getName,defineSettings]");
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testAuthorize()
    {
        $this->gateway->authorize(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testCompleteAuthorize()
    {
        $this->gateway->completeAuthorize(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testCapture()
    {
        $this->gateway->capture(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testPurchase()
    {
        $this->gateway->purchase(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testCompletePurchase()
    {
        $this->gateway->completePurchase(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testRefund()
    {
        $this->gateway->refund(array());
    }

    /**
     * @expectedException \Tala\Exception\UnsupportedMethodException
     */
    public function testVoid()
    {
        $this->gateway->void(array());
    }
}
