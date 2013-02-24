<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use ReflectionMethod;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\Exception\BadMethodCallException;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base payment gateway class
 */
abstract class AbstractGateway implements GatewayInterface
{
    protected $httpClient;

    /**
     * Create a new gateway instance
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->loadSettings();
    }

    public function authorize(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function completeAuthorize(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function capture(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function purchase(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function completePurchase(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function refund(array $options = null)
    {
        throw new BadMethodCallException;
    }

    public function void(array $options = null)
    {
        throw new BadMethodCallException;
    }

    /**
     * Get gateway short name
     *
     * This name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of this gateway.
     */
    public function getShortName()
    {
        return Helper::getGatewayShortName(get_class($this));
    }

    /**
     * Initialize gateway parameters
     */
    public function initialize($parameters)
    {
        Helper::initialize($this, $parameters);

        return $this;
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

        return $this;
    }

    public function getDefaultHttpClient()
    {
        return new HttpClient(
            '',
            array(
                'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
            )
        );
    }

    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function setHttpRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }

    public function getDefaultHttpRequest()
    {
        return HttpRequest::createFromGlobals();
    }
}
