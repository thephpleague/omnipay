<?php

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
