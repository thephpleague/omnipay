<?php

namespace Omnipay\Migs\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Migs Complete Purchase Request
 */
class ThreePartyCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->query->all();

        $hash = isset($data['vpc_SecureHash']) ? $data['vpc_SecureHash'] : null;
        if ($this->calculateHash($data) !== $hash) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new Response($this, $data);
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcpay';
    }
}
