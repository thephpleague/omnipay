<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Mollie iDeal Express Authorize Response
 */
class iDealAuthorizeResponse extends Response implements RedirectResponseInterface
{
    /**
     * By definition it wont be succesful - it will need the redirect.
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * It only is a redirect if the url is valid/exists.
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->getRedirectUrl()) {
            return true;
        }
        return false;
    }

    /**
     * Gets the URL from the redirect.
     *
     * @return null|string
     */
    public function getRedirectUrl()
    {
        if ($this->data instanceof \SimpleXMLElement
            && isset($this->data->order)
            && isset($this->data->order->URL)
        ) {
            return (string)$this->data->order->URL;
        }
        return null;
    }
}
