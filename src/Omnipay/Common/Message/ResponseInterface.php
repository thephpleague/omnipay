<?php

namespace Omnipay\Common\Message;

/**
 * Response interface
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
