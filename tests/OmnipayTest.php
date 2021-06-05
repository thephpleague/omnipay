<?php

namespace Omnipay\Tests;

use Omnipay\Common\Http\Client;
use Omnipay\Omnipay;

class OmnipayTest extends TestCase
{
    public function tearDown(): void
    {
        Omnipay::setFactory(null);

        parent::tearDown();
    }

    public function testGetFactory()
    {
        Omnipay::setFactory(null);

        $factory = Omnipay::getFactory();
        $this->assertInstanceOf('Omnipay\Common\GatewayFactory', $factory);
    }


    /**
     * Verify a new Client instance can be instantiated
     */
    public function testNewClient()
    {
        $client = new Client();

        $this->assertInstanceOf('Omnipay\Common\Http\Client', $client);
    }
}