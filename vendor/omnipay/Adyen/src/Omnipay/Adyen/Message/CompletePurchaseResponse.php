<?php

namespace Omnipay\Adyen\Message;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function getResponse()
    {
        $data = ($this->getData());
        
        return isset($data['allParams']) ? $data['allParams'] : $this;
    }

    public function isSuccessful()
    {
        $data = ($this->getData());
        
        return (isset($data['success']) and $data['success'] === true) ? true : false;
    }
}
