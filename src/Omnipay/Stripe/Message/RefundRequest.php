<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe\Message;

/**
 * Stripe Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = array();
        $data['amount'] = $this->getAmountInteger();

        return $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/charges/'.$this->getTransactionReference().'/refund';
    }
}
