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

/**
 * Redirect Response class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class RedirectResponse extends Response
{
    protected $redirectUrl;

    /**
     * Constructor.
     *
     * @param string $url The URL to redirect to
     */
    public function __construct($url)
    {
        $this->redirectUrl = $url;
    }

    /**
     * Does the request require a browser redirect?
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Was the request successful?
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Gets the response message from the payment gateway.
     */
    public function getMessage()
    {
        return null;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Perform the required redirect.
     */
    public function redirect($httpRedirectResponseClass = '\Symfony\Component\HttpFoundation\RedirectResponse')
    {
        $response = new $httpRedirectResponseClass($this->redirectUrl);
        $response->send();
    }
}
