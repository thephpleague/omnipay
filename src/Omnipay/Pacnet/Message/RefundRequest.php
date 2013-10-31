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

        $this->validate('amount', 'currency', 'transactionReference');

        $data['Amount'] = $this->getAmountInteger();
        $data['PymtType'] = 'cc_refund';
        $data['CurrencyCode'] = $this->getCurrency();
        $data['TemplateNumber'] = $this->getTransactionReference();

        $data['Signature'] = $this->generateSignature($data);

        return $data;
    }
}
