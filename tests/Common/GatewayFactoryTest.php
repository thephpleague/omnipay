<?php

namespace League\Omnipay\Common;

use Mockery as m;
use League\Omnipay\Tests\TestCase;

class GatewayFactoryTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        m::mock('alias:League\\Omnipay\\SpareChange\\TestGateway');
    }

    public function setUp()
    {
        $this->factory = new GatewayFactory;
    }

    public function testReplace()
    {
        $gateways = array('Foo');
        $this->factory->replace($gateways);

        $this->assertSame($gateways, $this->factory->all());
    }

    public function testRegister()
    {
        $this->factory->register('Bar');

        $this->assertSame(array('Bar'), $this->factory->all());
    }

    public function testRegisterExistingGateway()
    {
        $this->factory->register('Milky');
        $this->factory->register('Bar');
        $this->factory->register('Bar');

        $this->assertSame(array('Milky', 'Bar'), $this->factory->all());
    }

    public function testCreateShortName()
    {
        $gateway = $this->factory->create('SpareChange_Test');
        $this->assertInstanceOf('\\League\\Omnipay\\SpareChange\\TestGateway', $gateway);
    }

    public function testCreateFullyQualified()
    {
        $gateway = $this->factory->create('\\League\\Omnipay\\SpareChange\\TestGateway');
        $this->assertInstanceOf('\\League\\Omnipay\\SpareChange\\TestGateway', $gateway);
    }

    public function testCreateExistingClass()
    {
        $gateway = $this->factory->create('League\\Omnipay\\SpareChange\\TestGateway');
        $this->assertInstanceOf('\\League\\Omnipay\\SpareChange\\TestGateway', $gateway);
    }

    /**
     * @expectedException \League\Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Class '\League\Omnipay\Invalid\Gateway' not found
     */
    public function testCreateInvalid()
    {
        $gateway = $this->factory->create('Invalid');
    }
}
