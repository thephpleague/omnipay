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

use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\CreditCard;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base Gateway Test class
 *
 * Ensures all gateways conform to consistent standards
 */
abstract class GatewayTestCase extends TestCase
{
    protected $httpClient;
    protected $httpRequest;

    public function setUp()
    {
        $this->httpClient = new HttpClient;
    }

    /**
     * Helper method used by gateway test classes to generate a valid test credit card
     */
    public function getValidCard()
    {
        return new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'User',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2020',
            'cvv' => '123',
        ));
    }

    public function testConstructWithoutParameters()
    {
        // constructor should initialize default http client and request
        $class = get_class($this->gateway);
        $newGateway = new $class();

        $this->assertInstanceOf('Guzzle\Http\ClientInterface', $newGateway->getHttpClient());
    }

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

    public function testSupportsAuthorize()
    {
        $supportsAuthorize = $this->gateway->supportsAuthorize();
        $this->assertInternalType('boolean', $supportsAuthorize);

        if ($supportsAuthorize) {
            // authorize method should return RequestInterface
            $this->assertInstanceOf('Omnipay\\Common\\RequestInterface', $this->gateway->authorize());
        } else {
            // authorize method should throw BadMethodCallException
            $this->setExpectedException('Omnipay\\Common\\Exception\\BadMethodCallException');
            $this->gateway->authorize();
        }
    }

    public function testSupportsCapture()
    {
        $supportsCapture = $this->gateway->supportsCapture();
        $this->assertInternalType('boolean', $supportsCapture);

        if ($supportsCapture) {
            // capture method should return RequestInterface
            $this->assertInstanceOf('Omnipay\\Common\\RequestInterface', $this->gateway->capture());
        } else {
            // capture method should throw BadMethodCallException
            $this->setExpectedException('Omnipay\\Common\\Exception\\BadMethodCallException');
            $this->gateway->capture(array());
        }
    }

    public function testSupportsRefund()
    {
        $supportsRefund = $this->gateway->supportsRefund();
        $this->assertInternalType('boolean', $supportsRefund);

        if ($supportsRefund) {
            // refund method should return RequestInterface
            $this->assertInstanceOf('Omnipay\\Common\\RequestInterface', $this->gateway->refund());
        } else {
            // refund method should throw BadMethodCallException
            $this->setExpectedException('Omnipay\\Common\\Exception\\BadMethodCallException');
            $this->gateway->refund(array());
        }
    }

    public function testSupportsVoid()
    {
        $supportsVoid = $this->gateway->supportsVoid();
        $this->assertInternalType('boolean', $supportsVoid);

        if ($supportsVoid) {
            // void method should throw InvalidRequestException
            $this->setExpectedException('Omnipay\\Common\\Exception\\InvalidRequestException');
            $this->gateway->void(array());
        } else {
            // void method should throw BadMethodCallException
            $this->setExpectedException('Omnipay\\Common\\Exception\\BadMethodCallException');
            $this->gateway->void(array());
        }
    }

    public function testHttpClient()
    {
        $newHttpClient = new HttpClient;

        $this->assertSame($this->gateway, $this->gateway->setHttpClient($newHttpClient));
        $this->assertSame($newHttpClient, $this->gateway->getHttpClient());
    }

    public function testGetDefaultHttpClient()
    {
        $client = $this->gateway->getDefaultHttpClient();
        $curlOptions = $client->getConfig('curl.options');

        $this->assertInstanceOf('Guzzle\Http\Client', $client);
        $this->assertArrayHasKey(CURLOPT_CONNECTTIMEOUT, $curlOptions);
    }
}
