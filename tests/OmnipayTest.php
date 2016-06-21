<?php

namespace League\Omnipay;

use Mockery as m;
use League\Omnipay\Tests\TestCase;
use Interop\Container\ContainerInterface;
use League\Omnipay\Common\Http\ClientInterface;

class OmnipayTest extends TestCase
{
    public function tearDown()
    {
        Omnipay::setFactory(null);
    }

    public function testGetFactory()
    {
        Omnipay::setFactory(null);

        $factory = Omnipay::getFactory();
        $this->assertInstanceOf('League\Omnipay\Common\GatewayFactory', $factory);

        $container = $factory->getContainer();

        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertTrue($container->has(ClientInterface::class));
    }

    public function testSetFactory()
    {
        $factory = m::mock('League\Omnipay\Common\GatewayFactory');

        Omnipay::setFactory($factory);

        $this->assertSame($factory, Omnipay::getFactory());
    }

    public function testCreate()
    {
        $factory = m::mock('League\Omnipay\Common\GatewayFactory');
        $factory->shouldReceive('create')->with('some-argument')->once()->andReturn('some-result');

        Omnipay::setFactory($factory);

        $result = Omnipay::create('some-argument');
        $this->assertSame('some-result', $result);
    }
}
