<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\Pacnet\Message\AbstractRequest;

/**
 * Pacnet Submit Request
 */
class SubmitRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();

        $this->validate('amount', 'currency');

        $data['Amount'] = $this->getAmountInteger();
        $data['CurrencyCode'] = $this->getCurrency();

        return $data;
    }

    protected function generateSignature($data)
    {
        return hash_hmac(
            'sha1',
            $data['UserName'].
            $data['Timestamp'].
            $data['RequestID'].
            $data['PymtType'].
            $data['Amount'].
            $data['CurrencyCode'],
            $this->getSharedSecret()
        );
    }

    public function getEndpoint()
    {
        return ($this->getTestMode() ? $this->testEndPoint : $this->liveEndPoint) . 'submit';
    }
}
