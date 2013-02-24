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
    public function __construct(ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->loadSettings();
    }

    public function authorize($options = null)
    {
        throw new BadMethodCallException;
    }

    public function completeAuthorize($options = null)
    {
        throw new BadMethodCallException;
    }

    public function capture($options = null)
    {
        throw new BadMethodCallException;
    }

    public function purchase($options = null)
    {
        throw new BadMethodCallException;
    }

    public function completePurchase($options = null)
    {
        throw new BadMethodCallException;
    }

    public function refund($options = null)
    {
        throw new BadMethodCallException;
    }

    public function void($options = null)
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

    public function createResponse(RequestInterface $request, $responseData)
    {
        // request object knows which class its response should be
        $response = $request->createResponse($responseData)
            ->setGateway($this)
            ->setRequest($request);

        $request->setResponse($response);

        return $response;
    }
}
