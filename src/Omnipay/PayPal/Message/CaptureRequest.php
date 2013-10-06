<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('DoCapture');

        $this->validate('transactionReference', 'amount');

        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['AUTHORIZATIONID'] = $this->getTransactionReference();
        $data['COMPLETETYPE'] = 'Complete';

        return $data;
    }
}
