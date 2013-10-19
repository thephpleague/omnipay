<?php

namespace Omnipay\FirstData\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * FirstDataConnect Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['status']) && $this->data['status'] == 'APPROVED';
    }

    public function getTransactionReference()
    {
        return isset($this->data['oid']) ? $this->data['oid'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['status']) ? $this->data['status'] : null;
    }
}
