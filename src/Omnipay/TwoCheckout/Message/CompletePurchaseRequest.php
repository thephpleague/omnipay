<?php

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * 2Checkout Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $orderNo = $this->httpRequest->request->get('order_number');

        // strange exception specified by 2Checkout
        if ($this->getTestMode()) {
            $orderNo = '1';
        }

        $key = md5($this->getSecretWord().$this->getAccountNumber().$orderNo.$this->getAmount());
        if (strtolower($this->httpRequest->request->get('key')) !== $key) {
            throw new InvalidResponseException('Invalid key');
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
