<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Refund Request
 */
class RefundRequest extends SubmitRequest
{
    public function getData()
    {
        $data = parent::getData();

        $this->validate('transactionReference');

        $data['PymtType'] = 'cc_refund';
        $data['TemplateNumber'] = $this->getTransactionReference();

        $data['Signature'] = $this->generateSignature($data);

        return $data;
    }
}
