<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Capture Request
 */
class CaptureRequest extends SubmitRequest
{
    public function getData()
    {
        $data = parent::getData();

        $this->validate('transactionReference');

        $data['PymtType'] = 'cc_settle';
        $data['PreAuthNumber'] = $this->getTransactionReference();

        $data['Signature'] = $this->generateSignature($data);

        return $data;
    }
}
