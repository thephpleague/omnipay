<?php

namespace Omnipay\Adyen\Message;

/**
 * Adyen Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function generateResponseSignature()
    {
        return base64_encode(
            hash_hmac(
                'sha1',
                $this->getAmount().
                $this->getCurrency().
                $this->getShipBeforeDate().
                $this->getMerchantReference().
                $this->getSkinCode().
                $this->getMerchantAccount().
                $this->getSessionValidity(),
                $this->getSecret(),
                true
            )
        );
    }

    public function send()
    {
        $data = $this->getData();
        $data['success'] = ('AUTHORISED' == $this->httpRequest->query->get('authResult')) ? true : false;
        $data['allParams'] = $this->httpRequest->query->all();
        
        return new CompletePurchaseResponse($this, $data);
    }
}
