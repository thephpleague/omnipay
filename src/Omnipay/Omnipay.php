<?php

namespace Omnipay;

use Omnipay\Common\GatewayFactory;

/**
 * Omnipay class
 *
 * Provides static access to the gateway factory methods
 */
class Omnipay
{
    private static $factory;

    /**
     * Get the gateway factory
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

    public static function __callStatic($method, $parameters)
    {
        $factory = static::getFactory();

        return call_user_func_array(array($factory, $method), $parameters);
    }
}
