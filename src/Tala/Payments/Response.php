<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payments;

/**
 * Base Response class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class Response implements ResponseInterface
{
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
