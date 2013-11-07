<?php

namespace Omnipay\Adyen\Message;

/**
 * Adyen Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        
        $this->validate('merchantAccount', 'secret');
        
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
            
        $this->response = new CompletePurchaseResponse($this, $data);

        return $this->response;
    }
}
