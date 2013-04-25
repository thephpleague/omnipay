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
    public function getRefundType()
    {
        return $this->getParameter('refundType') ?: 'Full';
    }

    public function setRefundType($value)
    {
        return $this->setParameter('refundType', $value);
    }

    public function getData()
    {
        $data = $this->getBaseData('RefundTransaction');

        $this->validate('transactionReference');

        $data['TRANSACTIONID'] = $this->getTransactionReference();
        $data['REFUNDTYPE'] = $this->getRefundType();
        if ($this->getRefundType() != 'Full') {
            $data['AMT'] = $this->getAmountDecimal();
            $data['CURRENCYCODE'] = $this->getCurrency();
        }

        return $data;
    }
}
