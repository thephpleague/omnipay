<?php

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * PaymentExpress PxPay Complete Authorize Request
 */
class PxPayCompleteAuthorizeRequest extends PxPayAuthorizeRequest
{
    public function getData()
    {
        $result = $this->httpRequest->query->get('result');
        if (empty($result)) {
            throw new InvalidResponseException;
        }

        // validate dps response
        $data = new SimpleXMLElement('<ProcessResponse/>');
        $data->PxPayUserId = $this->getUsername();
        $data->PxPayKey = $this->getPassword();
        $data->Response = $result;

        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
