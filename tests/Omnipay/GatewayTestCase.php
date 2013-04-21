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

    public function testSupportsCompleteAuthorize()
    {
        $supportsCompleteAuthorize = $this->gateway->supportsCompleteAuthorize();
        $this->assertInternalType('boolean', $supportsCompleteAuthorize);

        if ($supportsCompleteAuthorize) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->completeAuthorize());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'completeAuthorize'));
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

    public function testSupportsCompletePurchase()
    {
        $supportsCompletePurchase = $this->gateway->supportsCompletePurchase();
        $this->assertInternalType('boolean', $supportsCompletePurchase);

        if ($supportsCompletePurchase) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->completePurchase());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'completePurchase'));
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

    public function testSupportsCreateCard()
    {
        $supportsCreate = $this->gateway->supportsCreateCard();
        $this->assertInternalType('boolean', $supportsCreate);

        if ($supportsCreate) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->createCard());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'createCard'));
        }
    }

    public function testSupportsDeleteCard()
    {
        $supportsDelete = $this->gateway->supportsDeleteCard();
        $this->assertInternalType('boolean', $supportsDelete);

        if ($supportsDelete) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->deleteCard());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'deleteCard'));
        }
    }

    public function testSupportsUpdateCard()
    {
        $supportsUpdate = $this->gateway->supportsUpdateCard();
        $this->assertInternalType('boolean', $supportsUpdate);

        if ($supportsUpdate) {
            $this->assertInstanceOf('Omnipay\Common\Message\RequestInterface', $this->gateway->updateCard());
        } else {
            $this->assertFalse(method_exists($this->gateway, 'updateCard'));
        }
    }

    public function testAuthorizeParameters()
    {
        if ($this->gateway->supportsAuthorize()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->authorize();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testCompleteAuthorizeParameters()
    {
        if ($this->gateway->supportsCompleteAuthorize()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->completeAuthorize();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testCaptureParameters()
    {
        if ($this->gateway->supportsCapture()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->capture();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testPurchaseParameters()
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);
            $value = uniqid();
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->purchase();
            $this->assertSame($value, $request->$getter());
        }
    }

    public function testCompletePurchaseParameters()
    {
        if ($this->gateway->supportsCompletePurchase()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->completePurchase();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testRefundParameters()
    {
        if ($this->gateway->supportsRefund()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->refund();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testVoidParameters()
    {
        if ($this->gateway->supportsVoid()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->void();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testCreateCardParameters()
    {
        if ($this->gateway->supportsCreateCard()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->createCard();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testDeleteCardParameters()
    {
        if ($this->gateway->supportsDeleteCard()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->deleteCard();
                $this->assertSame($value, $request->$getter());
            }
        }
    }

    public function testUpdateCardParameters()
    {
        if ($this->gateway->supportsUpdateCard()) {
            foreach ($this->gateway->getDefaultParameters() as $key => $default) {
                // set property on gateway
                $getter = 'get'.ucfirst($key);
                $setter = 'set'.ucfirst($key);
                $value = uniqid();
                $this->gateway->$setter($value);

                // request should have matching property, with correct value
                $request = $this->gateway->updateCard();
                $this->assertSame($value, $request->$getter());
            }
        }
    }
}
