<?php

namespace Omnipay\Payflow\Message;

/**
 * Payflow Capture Request
 */
class CaptureRequest extends AuthorizeRequest
{
    protected $action = 'D';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data['AMT'] = $this->getAmount();
        $data['ORIGID'] = $this->getTransactionReference();

        return $data;
    }
}
