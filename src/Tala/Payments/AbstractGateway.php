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

use Tala\Payments\Exception\BadMethodCallException;
use Tala\Payments\Request;

/**
 * Base payment gateway class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
abstract class AbstractGateway implements GatewayInterface
{
    protected $browser;

    public function __construct($settings = array())
    {
        $this->initialize($settings);
        $this->browser = new \Buzz\Browser();
        $this->httpRequest = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }

    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        switch ($prefix) {
            case 'get':
                return $this->$property;
                break;
            case 'set':
                $this->$property = isset($arguments[0]) ? $arguments[0] : null;
                break;
            default:
                throw new BadMethodCallException();
        }
    }

    public function initialize($settings)
    {
        $display = $this->getDefaultSettings();
        foreach ($display as $key => $value) {
            if (isset($settings[$key])) {
                $this->$key = $settings[$key];
            }
        }
    }

    public function getDefaultSettings()
    {
        return array();
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
