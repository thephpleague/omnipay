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

use Tala\Payments\AbstractParameterObject;
use Tala\Payments\Exception\BadMethodCallException;
use Tala\Payments\Request;

/**
 * Base payment gateway class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
abstract class AbstractGateway extends AbstractParameterObject implements GatewayInterface
{
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->setBrowser(new \Buzz\Browser());
        $this->setHttpRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function getValidParameters()
    {
        return array_merge(array('browser', 'httpRequest'), array_keys($this->getDefaultSettings()));
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
