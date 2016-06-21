<?php
/**
 * Omnipay class
 */

namespace League\Omnipay;

use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Omnipay\Common\GatewayFactory;
use League\Omnipay\Common\GatewayInterface;
use League\Omnipay\Common\Http\ClientInterface;
use League\Omnipay\Common\Container\HttpClientServiceProvider;
use League\Omnipay\Common\Container\ServerRequestServiceProvider;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Omnipay class
 *
 * Provides static access to the gateway factory methods.  This is the
 * recommended route for creation and establishment of payment gateway
 * objects via the standard GatewayFactory.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create(Express\Gateway::class);
 *
 *   // Initialise the gateway
 *   $gateway->initialize(...);
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $card = new CreditCard(...);
 *
 *   // Do an authorisation transaction on the gateway
 *   if ($gateway->supportsAuthorize()) {
 *       $gateway->authorize(...);
 *   } else {
 *       throw new \Exception('Gateway does not support authorize()');
 *   }
 * </code>
 *
 * For further code examples see the *omnipay-example* repository on github.
 *
 *
 * @see League\Omnipay\Common\GatewayFactory
 */
class Omnipay
{

    /**
     * Internal factory storage
     *
     * @var GatewayFactory
     */
    private static $factory;

    /**
     * Get the gateway factory
     *
     * Creates a new empty GatewayFactory if none has been set previously.
     *
     * @return GatewayFactory A GatewayFactory instance
     */
    public static function getFactory()
    {
        if (is_null(static::$factory)) {
            $container = new Container();

            // register service providers to set up default implementations
            $container->addServiceProvider(HttpClientServiceProvider::class);
            $container->addServiceProvider(ServerRequestServiceProvider::class);

            // register the reflection container as a delegate to enable auto wiring
            $container->delegate(
                new ReflectionContainer
            );

            static::$factory = new GatewayFactory($container);
        }

        return static::$factory;
    }

    /**
     * Set the gateway factory
     *
     * @param GatewayFactory $factory A GatewayFactory instance
     */
    public static function setFactory(GatewayFactory $factory = null)
    {
        static::$factory = $factory;
    }

    public static function create($gateway)
    {
        return static::getFactory()->create($gateway);
    }
}
