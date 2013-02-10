<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

/**
 * Base Gateway Test class
 *
 * Ensures all gateways conform to consistent standards
 */
abstract class BaseGatewayTest extends \PHPUnit_Framework_TestCase
{
    protected $gateway;

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

    public function testDefineSettingsReturnsArray()
    {
        $settings = $this->gateway->defineSettings();
        $this->assertInternalType('array', $settings);
    }

    public function testDefineSettingsHaveMatchingProperties()
    {
        $settings = $this->gateway->defineSettings();
        foreach ($settings as $key => $default) {
            $getter = 'get'.ucfirst($key);
            $setter = 'set'.ucfirst($key);
            $value = uniqid();

            $this->assertTrue(method_exists($this->gateway, $getter), "Gateway must implement $getter()");
            $this->assertTrue(method_exists($this->gateway, $setter), "Gateway must implement $setter()");

            $this->gateway->$setter($value);
            $this->assertSame($value, $this->gateway->$getter());
        }
    }

    public function testToArrayReturnsSettings()
    {
        $settings = $this->gateway->defineSettings();
        $output = $this->gateway->toArray();
        foreach ($settings as $key => $default) {
            $this->assertArrayHasKey($key, $output);
        }
    }

    public function getValidCard()
    {
        return new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2016',
            'cvv' => '123',
        ));
    }
}
