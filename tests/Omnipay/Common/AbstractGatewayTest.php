<?php

namespace Omnipay\Common;

use Mockery as m;
use Omnipay\TestCase;

class AbstractGatewayTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock("\Omnipay\Common\AbstractGateway[getName,getDefaultParameters,purchase]");
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
