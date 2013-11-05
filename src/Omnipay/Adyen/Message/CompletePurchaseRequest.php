<?php

namespace Omnipay\Adyen\Message;

/**
 * Adyen Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $this->validate('merchantAccount', 'secret', 'amount');

        return $this->httpRequest->request->all();
    }

    public function generateResponseSignature()
    {
        return base64_encode(
            hash_hmac(
                'sha1',
                $this->getPaymentAmount().
                $this->getCurrencyCode().
                $this->getShipBeforeDate().
                $this->getMerchantReference().
                $this->getSkinCode().
                $this->getMerchantAccount().
                $this->getSessionValidity(),
                $this->getSecret(),
                true)
        );
    }

    public function send()
    {
        $this->response = new CompletePurchaseResponse($this, $this->getData());

        return $this->response;
    }
}
