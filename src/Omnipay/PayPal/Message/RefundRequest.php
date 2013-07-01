<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('RefundTransaction');

        $this->validate('transactionReference');

        $data['TRANSACTIONID'] = $this->getTransactionReference();
        $data['REFUNDTYPE'] = 'Full';
        if ($this->getAmount() > 0) {
            $data['REFUNDTYPE'] = 'Partial';
            $data['AMT'] = $this->getAmount();
            $data['CURRENCYCODE'] = $this->getCurrency();
        }

        return $data;
    }
}
