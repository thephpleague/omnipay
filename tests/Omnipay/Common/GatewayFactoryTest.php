<?php

namespace Omnipay\Common;

use Mockery as m;
use Omnipay\TestCase;

class GatewayFactoryTest extends TestCase
{
    public function tearDown()
    {
        GatewayFactory::replace(array());
    }

    public function testReplace()
    {
        $gateways = array('Foo');
        GatewayFactory::replace($gateways);

        $this->assertSame($gateways, GatewayFactory::all());
    }

    public function testRegister()
    {
        GatewayFactory::register('Bar');

        $this->assertSame(array('Bar'), GatewayFactory::all());
    }

    public function testCreateShortName()
    {
        m::mock('alias:Omnipay\\SpareChange\\BankGateway');

        $gateway = GatewayFactory::create('SpareChange_Bank');
        $this->assertInstanceOf('\\Omnipay\\SpareChange\\BankGateway', $gateway);
    }

    public function testCreateFullyQualified()
    {
        m::mock('alias:Omnipay\\Tests\\FooGateway');

        $gateway = GatewayFactory::create('\\Omnipay\\Tests\\FooGateway');
        $this->assertInstanceOf('\\Omnipay\\Tests\\FooGateway', $gateway);
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Class '\Omnipay\Invalid\Gateway' not found
     */
    public function testCreateInvalid()
    {
        $gateway = GatewayFactory::create('Invalid');
    }
}
