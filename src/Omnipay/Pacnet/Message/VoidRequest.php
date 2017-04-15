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
        $data = parent::getData();

        $this->validate('transactionReference');

        $data['PymtType'] = 'cc_debit';
        $data['TrackingNumber'] = $this->getTransactionReference();
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
