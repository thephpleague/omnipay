<?php

namespace Omnipay\SecurePay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * SecurePay Direct Post Complete Purchase Response
 */
class DirectPostCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['summarycode']) && $this->data['summarycode'] == 1;
    }

    public function getMessage()
    {
        if (isset($this->data['restext'])) {
            return $this->data['restext'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['rescode'])) {
            return $this->data['rescode'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['txnid'])) {
            return $this->data['txnid'];
        }
    }
}
