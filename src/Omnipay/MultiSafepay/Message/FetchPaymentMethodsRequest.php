<?php

namespace Omnipay\MultiSafepay\Message;

use SimpleXMLElement;

class FetchPaymentMethodsRequest extends AbstractRequest
{
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><gateways/>');
        $data->addAttribute('ua', $this->userAgent);

        $merchant = $data->addChild('merchant');
        $merchant->addChild('account', $this->getAccountId());
        $merchant->addChild('site_id', $this->getSiteId());
        $merchant->addChild('site_secure_code', $this->getSiteCode());

        $customer = $data->addChild('customer');
        $customer->addChild('country', $this->getCountry());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint(),
            $this->getHeaders(),
            $data->asXML()
        )->send();

        return $this->response = new FetchPaymentMethodsResponse($this, $httpResponse->xml());
    }
}
