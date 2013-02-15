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

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\Exception\GatewayNotFoundException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GatewayFactory
{
    public static function create($type, ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $type = static::resolveType($type);

        if (!class_exists($type)) {
            throw new GatewayNotFoundException("Class '$type' not found");
        }

        if (null === $httpClient) {
            $httpClient = new HttpClient(
                '',
                array(
                    'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
                )
            );
        }

        if (null === $httpRequest) {
            $httpRequest = HttpRequest::createFromGlobals();
        }

        $gateway = new $type($httpClient, $httpRequest);

        return $gateway;
    }

    /**
     * Resolve a short gateway name to a full namespaced gateway class.
     *
     * Class names beginning with a namespace marker (\) are left intact.
     * Non-namespaced classes are expected to be in the \Omnipay namespace, e.g.:
     *
     *      \Custom\Gateway     => \Custom\Gateway
     *      \Custom_Gateway     => \Custom_Gateway
     *      Stripe              => \Omnipay\Stripe\Gateway
     *      PayPal\Express      => \Omnipay\PayPal\ExpressGateway
     *      PayPal_Express      => \Omnipay\PayPal\ExpressGateway
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

        return '\\Omnipay\\'.$type.'Gateway';
    }

    /**
     * Get a list of supported gateways, in friendly format (e.g. PayPal_Express)
     */
    public static function getAvailableGateways($directory = null)
    {
        $result = array();

        // find all gateways in the Billing directory
        $directory = dirname(__DIR__);
        $it = new RecursiveDirectoryIterator($directory);
        foreach (new RecursiveIteratorIterator($it) as $file) {
            $filepath = $file->getPathName();
            if ('Gateway.php' === substr($filepath, -11)) {
                // determine class name
                $type = substr($filepath, 0, -11);
                $type = str_replace(array($directory, DIRECTORY_SEPARATOR), array('', '_'), $type);
                $type = trim($type, '_');
                $class = static::resolveType($type);

                // ensure class exists and is not abstract
                if (class_exists($class)) {
                    $reflection = new ReflectionClass($class);
                    if (!$reflection->isAbstract() and
                        $reflection->implementsInterface('\\Omnipay\\Common\\GatewayInterface')) {
                        $result[] = $type;
                    }
                }
            }
        }

        return $result;
    }
}
