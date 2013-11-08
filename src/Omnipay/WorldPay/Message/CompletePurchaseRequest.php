<?php

namespace Omnipay\WorldPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * WorldPay Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $callbackPW = (string) $this->httpRequest->request->get('callbackPW');
        if ($callbackPW !== $this->getCallbackPassword()) {
            throw new InvalidResponseException("Invalid callback password");
        }

        return $this->httpRequest->request->all();
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if ($datas) {
            $datas = $doMerge ? array_merge($this->getData(), $datas) : $datas;
        } else {
            $datas = $this->getData();
        }

        return $this->response = new CompletePurchaseResponse($this, $datas);
    }
}
