<?php
/**
 * Omnipay Gateway Factory class
 */

namespace League\Omnipay\Common;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

/**
 * Omnipay Gateway Factory class
 *
 * This class abstracts a set of gateways that can be independently
 * registered, accessed, and used.
 *
 * Note that static calls to the Omnipay class are routed to this class by
 * the static call router (__callStatic) in Omnipay.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create(Omnipay\Express\Gateway::class);
 * </code>
 *
 * @see Omnipay\Omnipay
 */
class GatewayFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * GatewayFactory constructor.
     *
     * @param ContainerInterface $container
     */
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
     * @param  string  $gateway     Gateway classname
     * @throws ContainerException   If the gateway cannot be instantiated
     * @throws NotFoundException    If no such gateway is found
     * @return GatewayInterface     An object of class $class is created and returned
     */
    public function create($gateway)
    {
        return $this->container->get($gateway);
    }
}
