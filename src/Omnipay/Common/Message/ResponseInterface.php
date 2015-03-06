<?php
/**
 * Response interface
 */

namespace Omnipay\Common\Message;

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
    /**
     * Get the original request which generated this response
     *
     * @return RequestInterface
     */
    public function getRequest();

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
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled();

    /**
     * Response Message
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage();

    /**
     * Response code
     *
     * @return string A response code from the payment gateway
     */
    public function getCode();

    /**
     * Gateway Reference
     *
     * @return string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference();
}
