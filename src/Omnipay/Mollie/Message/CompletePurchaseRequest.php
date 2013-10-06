<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('partnerId');

        $data = $this->getBaseData();
        $data['a'] = 'check';
        $data['partnerid'] = $this->getPartnerId();
        $data['transaction_id'] = $this->httpRequest->query->get('transaction_id');

        return $data;
    }
}
