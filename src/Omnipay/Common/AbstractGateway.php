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
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base payment gateway class
 */
abstract class AbstractGateway implements GatewayInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * @var \Guzzle\Http\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

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
        $this->initialize();
    }

    public function getShortName()
    {
        return Helper::getGatewayShortName(get_class($this));
    }

    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }

        $this->parameters->add($parameters);

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters->all();
    }

    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    public function getCurrency()
    {
        return strtoupper($this->getParameter('currency'));
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function authorize(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function completeAuthorize(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function capture(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function purchase(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function completePurchase(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function refund(array $parameters = array())
    {
        throw new BadMethodCallException;
    }

    public function void(array $parameters = array())
    {
        throw new BadMethodCallException;
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

    /**
     * Create and initialize a request object using existing parameters from this gateway
     */
    protected function createRequest($class, array $parameters)
    {
        $obj = new $class($this->httpClient, $this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    protected function getDefaultHttpClient()
    {
        return new HttpClient(
            '',
            array(
                'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
            )
        );
    }

    protected function getDefaultHttpRequest()
    {
        return HttpRequest::createFromGlobals();
    }
}
