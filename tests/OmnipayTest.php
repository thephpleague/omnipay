<?php

namespace League\Omnipay;

use Interop\Container\ContainerInterface;
use League\Omnipay\Common\AbstractGateway;
use League\Omnipay\Common\Http\ClientInterface;
use Mockery as m;
use League\Omnipay\Tests\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class OmnipayTest extends TestCase
{

    public function testCreateGateway()
    {
        /** @var OmnipayTest_TestGateway $gateway */
        $gateway = Omnipay::create(OmnipayTest_TestGateway::class);

        $this->assertInstanceOf(OmnipayTest_TestGateway::class, $gateway);
        $this->assertInstanceOf(ClientInterface::class, $gateway->getProtectedHttpClient());
        $this->assertInstanceOf(ServerRequestInterface::class, $gateway->getProtectedHttpRequest());
    }

    public function testGetContainer()
    {
        $container = Omnipay::getContainer();

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    /**
     * @expectedException \League\Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Cannot create gateway Invalid
     */
    public function testCreateInvalid()
    {
        $gateway = Omnipay::create('Invalid');
    }
}

class OmnipayTest_TestGateway extends AbstractGateway {

    public function getName()
    {
        return 'TestGateway';
    }

    public function getProtectedHttpClient()
    {
        return $this->httpClient;
    }

    public function getProtectedHttpRequest()
    {
        return $this->httpRequest;
    }
}