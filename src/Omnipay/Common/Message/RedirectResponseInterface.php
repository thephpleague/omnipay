<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

/**
 * Response interface
 */
interface RedirectResponseInterface extends ResponseInterface
{
    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl();

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod();

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData();

    /**
     * Perform the required redirect.
     */
    public function redirect();
}
