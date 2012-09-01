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
 * Redirect Response class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class RedirectResponse implements ResponseInterface
{
    protected $redirectUrl;

    /**
     * Constructor.
     *
     * @param string $url   The URL to redirect to
     * @param array  $query Optional HTTP query string parameters
     */
    public function __construct($url, $query = array())
    {
        $this->redirectUrl = $url;
        if (!empty($query)) {
            $this->redirectUrl .= '?'.http_build_query($query);
        }
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
    public function redirect()
    {
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->redirectUrl);
        $response->send();
    }
}
