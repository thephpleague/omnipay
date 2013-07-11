<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->ewallet->status) && 'completed' === $this->data->ewallet->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference()
    {
        return isset($this->data->transaction->id) ? $this->data->transaction->id : null;
    }
}
