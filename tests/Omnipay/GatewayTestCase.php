<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

/**
 * Base Gateway Test class
 *
 * Ensures all gateways conform to consistent standards
 */
abstract class GatewayTestCase extends TestCase
{
    public function testGetNameNotEmpty()
    {
        $name = $this->gateway->getName();
        $this->assertNotEmpty($name);
        $this->assertInternalType('string', $name);
    }

    public function testGetShortNameNotEmpty()
    {
        $shortName = $this->gateway->getShortName();
        $this->assertNotEmpty($shortName);
        $this->assertInternalType('string', $shortName);
    }

    public function testGetDefaultParametersReturnsArray()
    {
        $settings = $this->gateway->getDefaultParameters();
        $this->assertInternalType('array', $settings);
    }

    public function testDefaultParametersHaveMatchingMethods()
    {
        $settings = $this->gateway->getDefaultParameters();
        foreach ($settings as $key => $default) {
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);
            $value = uniqid();

            $this->assertTrue(method_exists($this->gateway, $getter), "Gateway must implement $getter()");
            $this->assertTrue(method_exists($this->gateway, $setter), "Gateway must implement $setter()");

            // setter must return instance
            $this->assertSame($this->gateway, $this->gateway->$setter($value));
            $this->assertSame($value, $this->gateway->$getter());
        }
    }

    public function testTestMode()
    {
        $this->assertSame($this->gateway, $this->gateway->setTestMode(false));
        $this->assertSame(false, $this->gateway->getTestMode());

        $this->assertSame($this->gateway, $this->gateway->setTestMode(true));
        $this->assertSame(true, $this->gateway->getTestMode());
    }

    public function testCurrency()
    {
        // currency is normalized to uppercase
        $this->assertSame($this->gateway, $this->gateway->setCurrency('eur'));
        $this->assertSame('EUR', $this->gateway->getCurrency());
    }

    public function testPurchase()
    {
        // all gateways must implement purchase
        $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->purchase());
    }

    public function testSupportsAuthorize()
    {
        $supportsAuthorize = $this->gateway->supportsAuthorize();
        $this->assertInternalType('boolean', $supportsAuthorize);

        if ($supportsAuthorize) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->authorize());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'authorize'));
        }
    }

    public function testSupportsCapture()
    {
        $supportsCapture = $this->gateway->supportsCapture();
        $this->assertInternalType('boolean', $supportsCapture);

        if ($supportsCapture) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->capture());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'capture'));
        }
    }

    public function testSupportsRefund()
    {
        $supportsRefund = $this->gateway->supportsRefund();
        $this->assertInternalType('boolean', $supportsRefund);

        if ($supportsRefund) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->refund());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'refund'));
        }
    }

    public function testSupportsVoid()
    {
        $supportsVoid = $this->gateway->supportsVoid();
        $this->assertInternalType('boolean', $supportsVoid);

        if ($supportsVoid) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->void());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'void'));
        }
    }

    public function testSupportsStore()
    {
        $supportsStore = $this->gateway->supportsStore();
        $this->assertInternalType('boolean', $supportsStore);

        if ($supportsStore) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->store());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'store'));
        }
    }

    public function testSupportsUnstore()
    {
        $supportsUnstore = $this->gateway->supportsUnstore();
        $this->assertInternalType('boolean', $supportsUnstore);

        if ($supportsUnstore) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->unstore());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'unstore'));
        }
    }
}
