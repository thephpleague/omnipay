<?php

namespace Omnipay\SecurePay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * SecurePay Direct Post Complete Purchase Request
 */
class DirectPostCompletePurchaseRequest extends DirectPostAbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->request->all();

        if ($this->generateResponseFingerprint($data) !== $this->httpRequest->request->get('fingerprint')) {
            throw new InvalidRequestException('Invalid fingerprint');
        }

        return $data;
    }

    public function generateResponseFingerprint($data)
    {
        $fields = implode(
            '|',
            array(
                $data['merchant'],
                $this->getTransactionPassword(),
                $data['refid'],
                $this->getAmount(),
                $data['timestamp'],
                $data['summarycode'],
            )
        );

        return sha1($fields);
    }

    public function sendData($data)
    {
        return $this->response = new DirectPostCompletePurchaseResponse($this, $data);
    }
}
