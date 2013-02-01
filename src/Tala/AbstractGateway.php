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
 */
abstract class AbstractGateway extends AbstractParameterObject implements GatewayInterface
{
    protected $httpClient;
    protected $httpRequest;

    /**
     * Create a new gateway instance
     *
     * @param HttpClientInterface $httpClient  An HTTP client to make API calls with
     * @param HttpRequest         $httpRequest A Symfony HTTP request object
     */
    public function __construct(HttpClientInterface $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function getValidParameters()
    {
        return array_keys($this->getDefaultSettings());
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
