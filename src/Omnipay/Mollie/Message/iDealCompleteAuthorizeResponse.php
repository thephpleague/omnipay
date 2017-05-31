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

/**
 * Mollie iDeal Express Authorize Response
 */
class iDealCompleteAuthorizeResponse extends Response
{
    /**
     * Checks if the iDeal payment was successfully paid.
     * Note that this only returns true if the "payed=true" flag is set in the
     * returned XML, which Mollie only returns once.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->data instanceof \SimpleXMLElement
            && isset($this->data->order)
            && isset($this->data->order->payed)
            && (string)$this->data->order->payed == 'true'
            ) {
            return true;
        }
        return false;
    }
}
