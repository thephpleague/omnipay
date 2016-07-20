<?php

namespace League\Omnipay\Common;

use League\Omnipay\Common\Exception\InvalidArgumentException;
use League\Omnipay\SpareChange\TestGateway;
use League\Omnipay\SpareChange\InvalidGateway;
use Mockery as m;
use League\Omnipay\Omnipay;
use League\Omnipay\Tests\TestCase;
use Interop\Container\ContainerInterface;

class GatewayFactoryTest extends TestCase
{
    /**
     * @var GatewayFactory
     */
    private $factory;

    public static function setUpBeforeClass()
    {
        m::mock('alias:League\\Omnipay\\SpareChange\\InvalidGateway');
        
        $gatewayMock = m::mock('\\League\\Omnipay\\Common\\GatewayInterface');
        class_alias(get_class($gatewayMock), '\\League\\Omnipay\\SpareChange\\TestGateway');
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

    public function testCreateCorrectGateway()
    {
        $gateway = $this->factory->create(TestGateway::class);
        $this->assertInstanceOf(TestGateway::class, $gateway);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateInvalidGatewayInstance()
    {
        $gateway = $this->factory->create(InvalidGateway::class);
    }

    /**
     * @expectedException \Interop\Container\Exception\NotFoundException
     */
    public function testCreateInvalid()
    {
        $gateway = $this->factory->create('Invalid');
    }
}
