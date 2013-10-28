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
        $this->validate('UserName', 'Password', 'PRN');

        $data = array(
            'RAPIVersion'           => 2,
            'UserName'              => $this->getUserName(),
            'PRN'                   => $this->getPRN(),
            'Timestamp'             => gmdate('Y-m-d\TH:i:s.000\Z'),
            'RequestID'             => $this->getRequestID()
        );

        return $data;
    }

    protected function generateSignature($data)
    {
        return hash_hmac('sha1', $data['UserName'].$data['Timestamp'].$data['RequestID'].$data['PymtType'].$data['Amount'].$data['CurrencyCode'], $this->getPassword());
    }

    public function getEndPoint()
    {
        return ($this->getTestMode() ? $this->testEndPoint : $this->liveEndPoint).'submit';
    }
}
