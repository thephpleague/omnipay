<?php
/**
 * Redirect Response interface
 */

namespace Omnipay\Common\Message;

/**
 * Redirect Response interface
 *
 * This interface class defines the functionality of a response
 * that is a redirect response.  It extends the ResponseInterface
 * interface class with some extra functions relating to the
 * specifics of a redirect response from the gateway.
 *
 * @see ResponseInterface
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
