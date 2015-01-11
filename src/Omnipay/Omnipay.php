<?php
/**
 * Omnipay class
 */

namespace Omnipay;

use Omnipay\Common\GatewayFactory;

/**
 * Omnipay class
 *
 * Provides static access to the gateway factory methods.
 *
 * @see Omnipay\Common\GatewayFactory
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
            static::$factory = new GatewayFactory;
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
     * factory.  e.g. Omnipay::Mugwump(1, 2, 3, 4) is routed to the
     * factory's Mugwump method and passed the parameters 1, 2, 3, 4.
     *
     * @param mixed Parameters passed to the factory method.
     */
    public static function __callStatic($method, $parameters)
    {
        $factory = static::getFactory();

        return call_user_func_array(array($factory, $method), $parameters);
    }
}
