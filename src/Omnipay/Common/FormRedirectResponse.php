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

/**
 * Form Redirect Response class
 */
class FormRedirectResponse extends RedirectResponse
{
    /**
     * Constructor.
     *
     * @param string The URL to redirect to
     * @param array  Optional form POST data
     */
    public function __construct($url, $redirectData = array())
    {
        $this->redirectUrl = $url;
        $this->redirectData = $redirectData;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }
}
