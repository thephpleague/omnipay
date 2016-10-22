<?php

namespace League\Omnipay;

use League\Omnipay\Common\AbstractGateway;
use League\Omnipay\Common\Http\ClientInterface;
use Mockery as m;
use League\Omnipay\Tests\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class OmnipayTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        m::mock('alias:League\\Omnipay\\SpareChange\\TestGateway');
    }

    public function testCreate()
    {
        $httpClient = m::mock(ClientInterface::class);
        $httpRequest = m::mock(ServerRequestInterface::class);

        /** @var OmnipayTest_TestGateway $gateway */
        $gateway = Omnipay::create(OmnipayTest_TestGateway::class, $httpClient, $httpRequest);

        $this->assertInstanceOf(OmnipayTest_TestGateway::class, $gateway);
        $this->assertInstanceOf(ClientInterface::class, $gateway->getProtectedHttpClient());
        $this->assertInstanceOf(ServerRequestInterface::class, $gateway->getProtectedHttpRequest());
    }

    public function testCreateDefaults()
    {
        /** @var OmnipayTest_TestGateway $gateway */
        $gateway = Omnipay::create(OmnipayTest_TestGateway::class);

        $this->assertInstanceOf(OmnipayTest_TestGateway::class, $gateway);
        $this->assertInstanceOf(ClientInterface::class, $gateway->getProtectedHttpClient());
        $this->assertInstanceOf(ServerRequestInterface::class, $gateway->getProtectedHttpRequest());
    }

    /**
     * @expectedException \League\Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Class 'Invalid' not found
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