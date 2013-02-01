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
use Tala\Exception\GatewayNotFoundException;
use Tala\HttpClient\BuzzHttpClient;

class GatewayFactory
{
    public static function createGateway($type, $httpClient = null, $httpRequest = null, $parameters = array())
    {
        $type = static::resolveType($type);

        if ( ! class_exists($type)) {
            throw new GatewayNotFoundException("Class '$type' not found");
        }

        if (null === $httpClient) {
            $httpClient = new BuzzHttpClient(new \Buzz\Browser(new \Buzz\Client\Curl));
        }

        if (null === $httpRequest) {
            $httpRequest = HttpRequest::createFromGlobals();
        }

        $gateway = new $type($httpClient, $httpRequest);
        $gateway->initialize($parameters);

        return $gateway;
    }

    /**
     * Resolve a short gateway name to a full namespaced gateway class.
     *
     * Class names beginning with a namespace marker (\) are left intact.
     * Non-namespaced classes are expected to be in the \Tala\Billing namespace, e.g.:
     *
     *      \Custom\Gateway     => \Custom\Gateway
     *      \Custom_Gateway     => \Custom_Gateway
     *      Stripe              => \Tala\Billing\Stripe\Gateway
     *      PayPal\Express      => \Tala\Billing\PayPal\ExpressGateway
     *      PayPal_Express      => \Tala\Billing\PayPal\ExpressGateway
     */
    public static function resolveType($type)
    {
        if (0 === strpos($type, '\\')) {
            return $type;
        }

        // replace underscores with namespace marker, PSR-0 style
        $type = str_replace('_', '\\', $type);
        if (false === strpos($type, '\\')) {
            $type .= '\\';
        }

        return '\\Tala\\Billing\\'.$type.'Gateway';
    }
}
