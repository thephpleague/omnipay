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
        $this->validate('username', 'sharedSecret', 'paymentRoutingNumber', 'amount', 'currency');

        $data = array(
            'RAPIVersion'   => 2,
            'UserName'      => $this->getUsername(),
            'PRN'           => $this->getPaymentRoutingNumber(),
            'Timestamp'     => gmdate('Y-m-d\TH:i:s.000\Z'),
            'RequestID'     => $this->getRequestID(),
            'Amount'        => $this->getAmountInteger(),
            'CurrencyCode'  => $this->getCurrency()
        );

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
