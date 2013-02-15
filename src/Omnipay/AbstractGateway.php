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

use ReflectionMethod;
use Guzzle\Http\ClientInterface;
use Omnipay\Exception\UnsupportedMethodException;
use Omnipay\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base payment gateway class
 */
abstract class AbstractGateway implements GatewayInterface
{
    protected $httpClient;
    protected $httpRequest;

    /**
     * Create a new gateway instance
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;

        $this->loadSettings();
    }

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    abstract public function getName();

    /**
     * Define gateway settings, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
     * );
     */
    abstract public function defineSettings();

    public function authorize($options)
    {
        throw new UnsupportedMethodException;
    }

    public function completeAuthorize($options)
    {
        throw new UnsupportedMethodException;
    }

    public function capture($options)
    {
        throw new UnsupportedMethodException;
    }

    public function purchase($options)
    {
        throw new UnsupportedMethodException;
    }

    public function completePurchase($options)
    {
        throw new UnsupportedMethodException;
    }

    public function refund($options)
    {
        throw new UnsupportedMethodException;
    }

    public function void($options)
    {
        throw new UnsupportedMethodException;
    }

    /**
     * Get gateway short name
     *
     * This name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of this gateway.
     */
    public function getShortName()
    {
        $class = get_class($this);
        if (0 === strpos($class, 'Omnipay\\Billing\\')) {
            return trim(str_replace('\\', '_', substr($class, 16, -7)), '_');
        }

        return '\\'.$class;
    }

    /**
     * Initialize gateway parameters
     */
    public function initialize($parameters)
    {
        Helper::initialize($this, $parameters);
    }

    private function loadSettings()
    {
        foreach ($this->defineSettings() as $key => $value) {
            if (is_array($value)) {
                $this->$key = reset($value);
            } else {
                $this->$key = $value;
            }
        }
    }

    /**
     * Return current gateway settings as an array
     *
     * This method is useful if you need to store settings for various gateways in your database.
     */
    public function toArray()
    {
        $output = array();
        foreach ($this->defineSettings() as $key => $default) {
            $output[$key] = $this->{'get'.ucfirst($key)}();
        }

        return $output;
    }

    /**
     * Supports Authorize
     *
     * @return boolean True if this gateway supports the authorize() method
     */
    public function supportsAuthorize()
    {
        $reflectionMethod = new ReflectionMethod($this, 'authorize');

        return __CLASS__ !== $reflectionMethod->getDeclaringClass()->getName();
    }

    /**
     * Supports Capture
     *
     * @return boolean True if this gateway supports the capture() method
     */
    public function supportsCapture()
    {
        $reflectionMethod = new ReflectionMethod($this, 'capture');

        return __CLASS__ !== $reflectionMethod->getDeclaringClass()->getName();
    }

    /**
     * Supports Refund
     *
     * @return boolean True if this gateway supports the refund() method
     */
    public function supportsRefund()
    {
        $reflectionMethod = new ReflectionMethod($this, 'refund');

        return __CLASS__ !== $reflectionMethod->getDeclaringClass()->getName();
    }

    /**
     * Supports Void
     *
     * @return boolean True if this gateway supports the void() method
     */
    public function supportsVoid()
    {
        $reflectionMethod = new ReflectionMethod($this, 'void');

        return __CLASS__ !== $reflectionMethod->getDeclaringClass()->getName();
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function setHttpRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }
}
