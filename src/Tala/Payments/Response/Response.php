<?php

/*
 * This file is part of the Tala package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payments\Response;

/**
 * Base Response class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class Response implements ResponseInterface
{
    protected $success;
    protected $message;

    /**
     * Constructor.
     */
    public function __construct($success, $message = null)
    {
        $this->success = (bool) $success;
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
     * Was the request successful?
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * Get the response message from the payment gateway.
     */
    public function getMessage()
    {
        return $this->message;
    }
}
