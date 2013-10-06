<?php

namespace Omnipay\TargetPay\Message;

abstract class PurchaseRequest extends AbstractRequest
{
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $httpResponse = $this->httpClient->get(
            $this->getEndpoint().'?'.http_build_query($this->getData())
        )->send();

        return $this->response = new PurchaseResponse($this, $httpResponse->getBody(true));
    }
}
