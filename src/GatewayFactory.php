<?php
/**
 * Omnipay class
 */

namespace League\Omnipay;

use Interop\Container\ContainerInterface;
use League\Omnipay\Common\GatewayInterface;
use League\Omnipay\Common\Http\GuzzleClient;
use Psr\Http\Message\ServerRequestInterface;
use League\Omnipay\Common\Http\ClientInterface;
use League\Omnipay\Common\Exception\RuntimeException;
use Zend\Diactoros\ServerRequestFactory;

class GatewayFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Create a new gateway instance
     *
     * @param string $class Gateway name
     * @throws RuntimeException                 If no such gateway is found
     * @return GatewayInterface                 An object of class $class is created and returned
     */
    public function create($class)
    {
        try {
            $gateway = $this->container->get($class);
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf("Cannot create gateway %s: %s", $class, $e->getMessage()), 0, $e);
        }

        if (! $gateway instanceof GatewayInterface) {
            throw new RuntimeException(sprintf("Gateway must implement %s interface", GatewayInterface::class));
        }

        return $gateway;
    }
}
