<?php

namespace Omnipay\Pacnet\Message;

use Omnipay\Pacnet\Message\AbstractRequest;

/**
 * Pacnet Void Request
 */
class VoidRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('UserName', 'SharedSecret', 'PRN', 'transactionReference');

        $data = array(
            'RAPIVersion'       => 2,
            'UserName'          => $this->getUserName(),
            'PRN'               => $this->getPRN(),
            'Timestamp'         => gmdate('Y-m-d\TH:i:s.000\Z'),
            'RequestID'         => $this->getRequestID(),
            'TrackingNumber'    => $this->getTransactionReference(),
            'PymtType'          => 'cc_debit'
        );

        $data['Signature'] = $this->generateSignature($data);

        return $data;
    }

    protected function generateSignature($data)
    {
        return hash_hmac(
            'sha1',
            $data['UserName'] .
            $data['Timestamp'] .
            $data['RequestID'] .
            $data['TrackingNumber'],
            $this->getSharedSecret()
        );
    }

    public function getEndpoint()
    {
        return ($this->getTestMode() ? $this->testEndPoint : $this->liveEndPoint) . 'void';
    }
}
