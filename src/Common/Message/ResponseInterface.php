<?php
/**
 * Response interface
 */

namespace League\Omnipay\Common\Message;

/**
 * Response Interface
 *
 * This interface class defines the standard functions that any Omnipay response
 * interface needs to be able to provide.  It is an extension of MessageInterface.
 *
 * @see MessageInterface
 */
interface ResponseInterface extends MessageInterface
{
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CAPTURED = 'captured';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PENDING = 'pending';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_UNDEFINED = 'undefined';

    /**
     * Get the original request which generated this response
     *
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * Is the transaction completed?
     *
     * @return boolean
     */
    public function isCompleted();

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect();

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled();

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage();

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode();

    /**
     * Status
     *
     * @return null|string The status of the response
     */
    public function getStatus();

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference();
}
