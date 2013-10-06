<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Capture Request
 */
class PxPostCaptureRequest extends PxPostAuthorizeRequest
{
    protected $action = 'Complete';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data->DpsTxnRef = $this->getTransactionReference();
        $data->Amount = $this->getAmount();

        return $data;
    }
}
