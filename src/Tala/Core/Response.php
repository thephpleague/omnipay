<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core;

/**
 * Base Response class
 */
class Response implements ResponseInterface
{
    protected $data;
    protected $message;
    protected $gatewayReference;

    /**
     * Constructor.
     */
    public function __construct($gatewayReference = null, $message = null)
    {
        $this->gatewayReference = $gatewayReference;
        $this->message = $message;
    }

    /**
     * Does the request require a redirect?
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * Access raw data returned by the payment gateway.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the response message from the payment gateway.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get a reference provided by the gateway for this transaction.
     */
    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }
}
