<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;

/**
 * Redirect Response class
 */
class RedirectResponse extends AbstractResponse
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

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
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
        HttpRedirectResponse::create($this->redirectUrl)->send();
    }
}
