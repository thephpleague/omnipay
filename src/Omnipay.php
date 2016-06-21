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
 *   $gateway = Omnipay::create('ExpressGateway');
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
 * @method static array  all()
 * @method static array  replace(array $gateways)
 * @method static string register(string $className)
 * @method static array  find()
 * @method static array  getSupportedGateways()
 * @codingStandardsIgnoreStart
 * @method static GatewayInterface create(string $class, ClientInterface $httpClient = null, ServerRequestInterface $httpRequest = null)
 * @codingStandardsIgnoreEnd
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

    /**
     * Static function call router.
     *
     * All other function calls to the Omnipay class are routed to the
     * factory.  e.g. Omnipay::getSupportedGateways(1, 2, 3, 4) is routed to the
     * factory's getSupportedGateways method and passed the parameters 1, 2, 3, 4.
     *
     * Example:
     *
     * <code>
     *   // Create a gateway for the PayPal ExpressGateway
     *   $gateway = Omnipay::create('ExpressGateway');
     * </code>
     *
     * @see GatewayFactory
     *
     * @param string $method     The factory method to invoke.
     * @param array  $parameters Parameters passed to the factory method.
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $factory = static::getFactory();

        return call_user_func_array(array($factory, $method), $parameters);
    }
}
