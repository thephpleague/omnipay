<?php

namespace Tala\Payments;

use Tala\Payments\Exception\BadMethodCallException;

/**
 * Base payment gateway class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
abstract class AbstractGateway implements GatewayInterface
{
    /**
     * Authorizes a new payment.
     */
    public function authorize($amount, $source, $options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Handles return from an authorization.
     */
    public function authorizeReturn($options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Capture an authorized payment.
     */
    public function capture($transactionId, $options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Creates a new charge.
     */
    public function purchase($amount, $source, $options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Handle return from a purchase.
     */
    public function purchaseReturn($options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Refund an existing transaction.
     * Generally this will refund a transaction which has been already submitted for processing,
     * and may be called up to 30 days after submitting the transaction.
     */
    public function refund($transactionReference, $options)
    {
        throw new BadMethodCallException();
    }

    /**
     * Void an existing transaction.
     * Generally this will prevent the transaction from being submitted for processing,
     * and can only be called up to 24 hours after submitting the transaction.
     */
    public function void($transactionReference, $options)
    {
        throw new BadMethodCallException();
    }
}
