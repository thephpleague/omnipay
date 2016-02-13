<?php

namespace League\Omnipay;

use Mockery as m;
use League\Omnipay\Tests\TestCase;

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
    }

    public function testSetFactory()
    {
        $factory = m::mock('League\Omnipay\Common\GatewayFactory');

        Omnipay::setFactory($factory);

        $this->assertSame($factory, Omnipay::getFactory());
    }

    public function testCallStatic()
    {
        $factory = m::mock('League\Omnipay\Common\GatewayFactory');
        $factory->shouldReceive('testMethod')->with('some-argument')->once()->andReturn('some-result');

        Omnipay::setFactory($factory);

        $result = Omnipay::testMethod('some-argument');
        $this->assertSame('some-result', $result);
    }
}
