<?php

namespace Tala\Payments\Gateway;

/**
 * Payment gateway interface
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
interface GatewayInterface
{
    /**
     * Authorizes a new payment.
     */
    public function authorize($amount, $source, $options);

    /**
     * Handles return from an authorization.
     */
    public function authorizeReturn($options);

    /**
     * Capture an authorized payment.
     */
    public function capture($transactionId, $options);

    /**
     * Creates a new charge.
     */
    public function purchase($amount, $source, $options);

    /**
     * Handle return from a purchase.
     */
    public function purchaseReturn($options);

    /**
     * Refund an existing transaction.
     * Generally this will refund a transaction which has been already submitted for processing,
     * and may be called up to 30 days after submitting the transaction.
     */
    public function refund($transactionReference, $options);

    /**
     * Void an existing transaction.
     * Generally this will prevent the transaction from being submitted for processing,
     * and can only be called up to 24 hours after submitting the transaction.
     */
    public function void($transactionReference, $options);
}
