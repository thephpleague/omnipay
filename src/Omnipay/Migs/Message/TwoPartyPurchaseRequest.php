<?php

namespace Omnipay\Migs\Message;

/**
 * Migs Purchase Request
 */
class TwoPartyPurchaseRequest extends AbstractRequest
{
    protected $action = 'pay';

    public function getData()
    {
        $this->validate('amount', 'transactionId', 'card');

        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['vpc_CardNum'] = $this->getCard()->getNumber();
        $data['vpc_CardExp'] = $this->getCard()->getExpiryDate('ym');
        $data['vpc_CardSecurityCode'] = $this->getCard()->getCvv();
        $data['vpc_SecureHash']  = $this->calculateHash($data);

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcdps';
    }
}
