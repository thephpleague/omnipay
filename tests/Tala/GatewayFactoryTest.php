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

class GatewayFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new GatewayFactory;
    }

    public function testCreateGateway()
    {
        $gateway = $this->factory->createGateway('Stripe');
        $this->assertInstanceOf('\\Tala\\Billing\\Stripe\\Gateway', $gateway);
    }

    /**
     * @expectedException \Tala\Exception\GatewayNotFoundException
     * @expectedExceptionMessage Class '\Tala\Billing\Invalid\Gateway' not found
     */
    public function testCreateGatewayInvalid()
    {
        $gateway = $this->factory->createGateway('Invalid');
    }

    /**
     * Type with namespace should simply be returned as is
     */
    public function testResolveTypeExistingNamespace()
    {
        $class = $this->factory->resolveType('\\Custom\\Gateway');
        $this->assertEquals('\\Custom\\Gateway', $class);
    }

    /**
     * Type with namespace marker should be left intact, even if it contains an underscore
     */
    public function testResolveTypeExistingNamespaceUnderscore()
    {
        $class = $this->factory->resolveType('\\Custom_Gateway');
        $this->assertEquals('\\Custom_Gateway', $class);
    }

    public function testResolveSimple()
    {
        $class = $this->factory->resolveType('Stripe');
        $this->assertEquals('\\Tala\\Billing\\Stripe\\Gateway', $class);
    }

    public function testResolvePartialNamespace()
    {
        $class = $this->factory->resolveType('PayPal\\Express');
        $this->assertEquals('\\Tala\\Billing\\PayPal\\ExpressGateway', $class);
    }

    /**
     * Underscored types should be resolved in a PSR-0 fashion
     */
    public function testResolveUnderscoreNamespace()
    {
        $class = $this->factory->resolveType('PayPal_Express');
        $this->assertEquals('\\Tala\\Billing\\PayPal\\ExpressGateway', $class);
    }
}
