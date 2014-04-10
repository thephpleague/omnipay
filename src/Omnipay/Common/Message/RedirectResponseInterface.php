<?php

namespace Omnipay\Common\Message;

/**
 * Redirect Response interface
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
