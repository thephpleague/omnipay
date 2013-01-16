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

use BadMethodCallException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Tala\AbstractParameterObject;
use Tala\HttpClient\HttpClientInterface;
use Tala\Request;

/**
 * Base payment gateway class
 *
 * @author Adrian Macneil <adrian@adrianmacneil.com>
 * @author Alexander Deruwe <alexander.deruwe@gmail.com>
 */
abstract class AbstractGateway extends AbstractParameterObject implements GatewayInterface
{
    private $httpClient;
    private $httpRequest;

    /**
     * @param HttpClientInterface $httpClient  Tala HTTP Client
     * @param HttpRequest         $httpRequest Symfony2 HTTP Request
     * @param array               $parameters  Additional gateway specific parameters
     */
    public function __construct(HttpClientInterface $httpClient, HttpRequest $httpRequest, $parameters = array())
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;

        parent::__construct($parameters);
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function getValidParameters()
    {
        return array_merge(array('httpClient', 'httpRequest'), array_keys($this->getDefaultSettings()));
    }

    /**
     * Authorizes a new payment.
     */
    public function authorize(Request $request, $source)
    {
        throw new BadMethodCallException();
    }

    /**
     * Handles return from an authorization.
     */
    public function completeAuthorize(Request $request)
    {
        throw new BadMethodCallException();
    }

    /**
     * Capture an authorized payment.
     */
    public function capture(Request $request)
    {
        throw new BadMethodCallException();
    }

    /**
     * Creates a new charge.
     */
    public function purchase(Request $request, $source)
    {
        throw new BadMethodCallException();
    }

    /**
     * Handle return from a purchase.
     */
    public function completePurchase(Request $request)
    {
        throw new BadMethodCallException();
    }

    /**
     * Refund an existing transaction.
     * Generally this will refund a transaction which has been already submitted for processing,
     * and may be called up to 30 days after submitting the transaction.
     */
    public function refund(Request $request)
    {
        throw new BadMethodCallException();
    }

    /**
     * Void an existing transaction.
     * Generally this will prevent the transaction from being submitted for processing,
     * and can only be called up to 24 hours after submitting the transaction.
     */
    public function void(Request $request)
    {
        throw new BadMethodCallException();
    }
}
