<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Refund Request
 */
class RefundRequest extends SubmitRequest
{
    public function getTemplateNumber()
    {
        return $this->getParameter('TemplateNumber');
    }

    public function setTemplateNumber($value)
    {
        return $this->setParameter('TemplateNumber', $value);
    }

    public function getData()
    {
        $data = parent::getData();

        $this->validate('TemplateNumber');

        $data['Amount'] = $this->getAmountInteger();
        $data['PymtType'] = 'cc_refund';
        $data['CurrencyCode'] = $this->getCurrency();
        $data['TemplateNumber'] = $this->getTemplateNumber();

        $data['Signature'] = $this->generateSignature($data);

        return $data;
    }
}
