<?php

namespace League\Omnipay\Common;

use Interop\Container\ContainerInterface;
use League\Omnipay\Common\Http\ClientInterface;
use Mockery as m;
use League\Omnipay\Common\Message\AbstractRequest;
use League\Omnipay\Tests\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class AbstractGatewayTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = m::mock('\League\Omnipay\Common\AbstractGateway')->makePartial();
        $this->gateway->initialize();
    }

    public function testConstruct()
    {
        $container = m::mock(ContainerInterface::class);
        $this->gateway = new AbstractGatewayTest_MockAbstractGateway($container);
        $this->assertInstanceOf(ContainerInterface::class, $this->gateway->getProtectedContainer());
        $this->assertSame(array(), $this->gateway->getParameters());
    }

    public function testInitializeDefaults()
    {
        $defaults = array(
            'currency' => 'AUD', // fixed default type
            'username' => array('joe', 'fred'), // enum default type
        );
        $this->gateway->shouldReceive('getDefaultParameters')->once()
            ->andReturn($defaults);

        $this->gateway->initialize();

        $expected = array(
            'currency' => 'AUD',
            'username' => 'joe',
        );
        $this->assertSame($expected, $this->gateway->getParameters());
    }

    public function testInitializeParameters()
    {
        $this->gateway->shouldReceive('getDefaultParameters')->once()
            ->andReturn(array('currency' => 'AUD'));

        $this->gateway->initialize(array(
            'currency' => 'USD',
            'unknown' => '42',
        ));

        $this->assertSame(array('currency' => 'USD', 'unknown' => '42'), $this->gateway->getParameters());
    }

    public function testGetDefaultParameters()
    {
        $this->assertSame(array(), $this->gateway->getDefaultParameters());
    }

    public function testGetParameters()
    {
        $this->gateway->setTestMode(true);

        $this->assertSame(array('testMode' => true), $this->gateway->getParameters());
    }

    public function testTestMode()
    {
        $this->assertSame($this->gateway, $this->gateway->setTestMode(true));
        $this->assertSame(true, $this->gateway->getTestMode());
    }

    public function testCurrency()
    {
        $this->assertSame($this->gateway, $this->gateway->setCurrency('USD'));
        $this->assertSame('USD', $this->gateway->getCurrency());
    }

    public function testSupportsAuthorize()
    {
        $this->assertFalse($this->gateway->supportsAuthorize());
    }

    public function testSupportsCompleteAuthorize()
    {
        $this->assertFalse($this->gateway->supportsCompleteAuthorize());
    }

    public function testSupportsCapture()
    {
        $this->assertFalse($this->gateway->supportsCapture());
    }

    public function testSupportsPurchase()
    {
        $this->assertFalse($this->gateway->supportsPurchase());
    }

    public function testSupportsCompletePurchase()
    {
        $this->assertFalse($this->gateway->supportsCompletePurchase());
    }

    public function testSupportsRefund()
    {
        $this->assertFalse($this->gateway->supportsRefund());
    }

    public function testSupportsVoid()
    {
        $this->assertFalse($this->gateway->supportsVoid());
    }

    public function testSupportsCreateCard()
    {
        $this->assertFalse($this->gateway->supportsCreateCard());
    }

    public function testSupportsDeleteCard()
    {
        $this->assertFalse($this->gateway->supportsDeleteCard());
    }

    public function testSupportsUpdateCard()
    {
        $this->assertFalse($this->gateway->supportsUpdateCard());
    }

    public function testSupportsAcceptNotification()
    {
        $this->assertFalse($this->gateway->supportsAcceptNotification());
    }

    public function testCreateRequest()
    {
        $container = m::mock(ContainerInterface::class);
        $httpClient = m::mock(ClientInterface::class);
        $httpRequest = m::mock(ServerRequestInterface::class);
        $request = new AbstractGatewayTest_MockAbstractRequest($httpClient, $httpRequest);
        $container->shouldReceive('get')->andReturn($request);
        $this->gateway = new AbstractGatewayTest_MockAbstractGateway($container);
        $request = $this->gateway->callCreateRequest(
            '\League\Omnipay\Common\AbstractGatewayTest_MockAbstractRequest',
            array('currency' => 'THB')
        );

        $this->assertSame(array('currency' => 'THB'), $request->getParameters());
    }
}

class AbstractGatewayTest_MockAbstractGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Mock Gateway Implementation';
    }

    public function getProtectedContainer()
    {
        return $this->container;
    }

    public function callCreateRequest($class, array $parameters)
    {
        return $this->createRequest($class, $parameters);
    }
}

class AbstractGatewayTest_MockAbstractRequest extends AbstractRequest
{
    public function getData() {}
    public function sendData($data) {}
}
