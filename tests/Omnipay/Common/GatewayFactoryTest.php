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

use Omnipay\TestCase;

class GatewayFactoryTest extends TestCase
{
    public function testCreate()
    {
        $gateway = GatewayFactory::create('Stripe');
        $this->assertInstanceOf('\\Omnipay\\Stripe\\Gateway', $gateway);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\GatewayNotFoundException
     * @expectedExceptionMessage Class '\Omnipay\Invalid\Gateway' not found
     */
    public function testCreateInvalid()
    {
        $gateway = GatewayFactory::create('Invalid');
    }

    /**
     * Type with namespace should simply be returned as is
     */
    public function testResolveTypeExistingNamespace()
    {
        $class = GatewayFactory::resolveType('\\Custom\\Gateway');
        $this->assertEquals('\\Custom\\Gateway', $class);
    }

    /**
     * Type with namespace marker should be left intact, even if it contains an underscore
     */
    public function testResolveTypeExistingNamespaceUnderscore()
    {
        $class = GatewayFactory::resolveType('\\Custom_Gateway');
        $this->assertEquals('\\Custom_Gateway', $class);
    }

    public function testResolveSimple()
    {
        $class = GatewayFactory::resolveType('Stripe');
        $this->assertEquals('\\Omnipay\\Stripe\\Gateway', $class);
    }

    public function testResolvePartialNamespace()
    {
        $class = GatewayFactory::resolveType('PayPal\\Express');
        $this->assertEquals('\\Omnipay\\PayPal\\ExpressGateway', $class);
    }

    /**
     * Underscored types should be resolved in a PSR-0 fashion
     */
    public function testResolveUnderscoreNamespace()
    {
        $class = GatewayFactory::resolveType('PayPal_Express');
        $this->assertEquals('\\Omnipay\\PayPal\\ExpressGateway', $class);
    }

    public function testGetAvailableGateways()
    {
        $gateways = GatewayFactory::getAvailableGateways();
        $this->assertContains('PayPal_Express', $gateways);
        $this->assertContains('Stripe', $gateways);
    }
}
