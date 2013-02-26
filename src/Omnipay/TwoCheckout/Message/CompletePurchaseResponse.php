<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * 2Checkout Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }

    public function getTransactionReference()
    {
        return isset($this->data['order_number']) ? $this->data['order_number'] : null;
    }
}
