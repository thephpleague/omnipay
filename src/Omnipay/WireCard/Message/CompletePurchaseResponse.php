<?php

namespace Omnipay\WireCard\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * WireCard Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['transStatus']) 
            && 'Y' === $this->data['transStatus'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['transId']) 
            ? $this->data['transId'] 
            : null;
    }

    public function getMessage()
    {
        return isset($this->data['rawAuthMessage']) 
            ? $this->data['rawAuthMessage'] 
            : null;
    }
}
