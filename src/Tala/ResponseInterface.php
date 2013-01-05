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

/**
 * Response interface
 */
interface ResponseInterface
{
    /**
     * Does the request require a redirect?
     */
    public function isRedirect();

    /**
     * Access raw data returned by the payment gateway.
     */
    public function getData();

    /**
     * Gets the response message from the payment gateway.
     */
    public function getMessage();

    /**
     * Get a reference provided by the gateway for this transaction.
     */
    public function getGatewayReference();
}
