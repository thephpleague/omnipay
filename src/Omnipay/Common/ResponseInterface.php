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

/**
 * Response interface
 */
interface ResponseInterface
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful();

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect();

    /**
     * Response Data
     *
     * The format of this will vary from gateway to gateway. For example, some gateways
     * will return a SimpleXMLElement, while others may return an array.
     *
     * @return mixed Raw data returned from the payment gateway request
     */
    public function getData();

    /**
     * Response Message
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage();

    /**
     * Gateway Reference
     *
     * @return string A reference provided by the gateway to represent this transaction
     */
    public function getGatewayReference();
}
