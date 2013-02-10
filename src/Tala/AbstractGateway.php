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

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Tala\Exception\UnsupportedMethodException;
use Tala\HttpClient\HttpClientInterface;
use Tala\Request;

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
     * @param HttpClientInterface $httpClient  An HTTP client to make API calls with
     * @param HttpRequest         $httpRequest A Symfony HTTP request object
     */
    public function __construct(HttpClientInterface $httpClient, HttpRequest $httpRequest)
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
        if (0 === strpos($class, 'Tala\\Billing\\')) {
            return trim(str_replace('\\', '_', substr($class, 13, -7)), '_');
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

    public function toArray()
    {
        $output = array();
        foreach ($this->defineSettings() as $key => $default) {
            $output[$key] = $this->{'get'.ucfirst($key)}();
        }

        return $output;
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
}
