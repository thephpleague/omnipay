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
 * Mollie Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('partnerId', 'amount', 'issuer', 'returnUrl', 'notifyUrl');

        $data = $this->getBaseData();
        $data['a'] = 'fetch';
        $data['partnerid'] = $this->getPartnerId();
        $data['returnurl'] = $this->getReturnUrl();
        $data['reporturl'] = $this->getNotifyUrl();
        $data['bank_id'] = $this->getIssuer();
        $data['amount'] = $this->getAmountInteger();
        $data['description'] = $this->getDescription();

        return $data;
    }
}
