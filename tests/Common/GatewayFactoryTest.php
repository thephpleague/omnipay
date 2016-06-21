<?php

namespace League\Omnipay\Common;

use Mockery as m;
use League\Omnipay\Omnipay;
use League\Omnipay\Tests\TestCase;
use Interop\Container\ContainerInterface;

class GatewayFactoryTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        m::mock('alias:League\\Omnipay\\SpareChange\\TestGateway');
    }

    public function setUp()
    {
        $this->factory = Omnipay::getFactory();
    }

    public function testConstruct()
    {
        $container = m::mock(ContainerInterface::class);
        $factory = new GatewayFactory($container);

        $this->assertInstanceOf(ContainerInterface::class, $factory->getContainer());
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
     * @expectedException \Interop\Container\Exception\NotFoundException
     */
    public function testCreateInvalid()
    {
        $gateway = $this->factory->create('Invalid');
    }
}
