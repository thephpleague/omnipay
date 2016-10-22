<?php
/**
 * Omnipay class
 */

namespace League\Omnipay;

use League\Omnipay\Common\GatewayInterface;
use League\Omnipay\Common\Http\GuzzleClient;
use Psr\Http\Message\ServerRequestInterface;
use League\Omnipay\Common\Http\ClientInterface;
use League\Omnipay\Common\Exception\RuntimeException;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Omnipay class
 *
 * Provides static access to create gateways with common dependencies.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the PayPal ExpressGateway
 *   $gateway = Omnipay::create(Omnipay\PayPal\ExpressGateway::class);
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
 */
class Omnipay
{
    /**
     * Create a new gateway instance
     *
     * @param string $class Gateway name
     * @param ClientInterface|null $httpClient A HTTP Client implementation
     * @param ServerRequestInterface|null $httpRequest A HTTP Request implementation
     * @throws RuntimeException                 If no such gateway is found
     * @return GatewayInterface                 An object of class $class is created and returned
     */
    public static function create($class, ClientInterface $httpClient = null, ServerRequestInterface $httpRequest = null)
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        $httpClient = $httpClient ?: static::getDefaultHttpClient();
        $httpRequest = $httpRequest ?: static::getDefaultHttpRequest();

        return new $class($httpClient, $httpRequest);
    }

    /**
     * Get the global default HTTP client.
     *
     * @return ClientInterface
     */
    public static function getDefaultHttpClient()
    {
        return new GuzzleClient();
    }

    /**
     * Get the global default HTTP request.
     *
     * @return ServerRequestInterface
     */
    public static function getDefaultHttpRequest()
    {
        return ServerRequestFactory::fromGlobals();
    }
}