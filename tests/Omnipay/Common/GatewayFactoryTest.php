<?php

namespace Omnipay\Common;

use Omnipay\TestCase;

class GatewayFactoryTest extends TestCase
{
    public function testCreate()
    {
        $gateway = GatewayFactory::create('Stripe');
        $this->assertInstanceOf('\\Omnipay\\Stripe\\Gateway', $gateway);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Class '\Omnipay\Invalid\Gateway' not found
     */
    public function testCreateInvalid()
    {
        $gateway = GatewayFactory::create('Invalid');
    }

    public function testFind()
    {
        $gateways = GatewayFactory::find();
        $this->assertContains('PayPal_Express', $gateways);
        $this->assertContains('Stripe', $gateways);
    }
}
