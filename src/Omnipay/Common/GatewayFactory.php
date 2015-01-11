<?php
/**
 * Omnipay Gateway Factory class
 */

namespace Omnipay\Common;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Omnipay Gateway Factory class
 *
 * This class abstracts a set of gateways that can be independently
 * registered, accessed, and used.
 */
class GatewayFactory
{
    /**
     * Internal storage for all available gateways
     *
     * @var array
     */
    private $gateways = array();

    /**
     * All available gateways
     *
     * @return array An array of gateway names
     */
    public function all()
    {
        return $this->gateways;
    }

    /**
     * Replace the list of available gateways
     *
     * @param array $gateways An array of gateway names
     */
    public function replace(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * Register a new gateway
     *
     * @param string $className Gateway name
     */
    public function register($className)
    {
        if (!in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        }
    }

    /**
     * Automatically find and register all officially supported gateways
     *
     * @return array An array of gateway names
     */
    public function find()
    {
        foreach ($this->getSupportedGateways() as $gateway) {
            $class = Helper::getGatewayClassName($gateway);
            if (class_exists($class)) {
                $this->register($gateway);
            }
        }

        ksort($this->gateways);

        return $this->all();
    }

    /**
     * Create a new gateway instance
     *
     * @param string               $class       Gateway name
     * @param ClientInterface|null $httpClient  A Guzzle HTTP Client implementation
     * @param HttpRequest|null     $httpRequest A Symfony HTTP Request implementation
     */
    public function create($class, ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $class = Helper::getGatewayClassName($class);

        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($httpClient, $httpRequest);
    }

    /**
     * Get a list of supported gateways which may be available
     *
     * @return array
     */
    public function getSupportedGateways()
    {
        $package = json_decode(file_get_contents(__DIR__.'/../../../composer.json'), true);

        return $package['extra']['gateways'];
    }
}
