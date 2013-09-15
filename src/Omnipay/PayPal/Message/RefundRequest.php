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
    const METHOD = 'RefundTransaction';
    const TYPE_FULL = 'Full';
    const TYPE_PARTIAL = 'Partial';

    public function getData()
    {
        $data = $this->getBaseData(self::METHOD);

        $this->validate('transactionReference');

        $data['TRANSACTIONID'] = $this->getTransactionReference();
        $data['REFUNDTYPE'] = self::TYPE_FULL;
        if ($this->getAmount() > 0) {
            $data['REFUNDTYPE'] = self::TYPE_PARTIAL;
            $data['AMT'] = $this->getAmount();
            $data['CURRENCYCODE'] = $this->getCurrency();
        }

        return $data;
    }
}
