<?php

namespace Omnipay\Mollie\Message;

/**
 * Mollie Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://secure.mollie.nl/xml/ideal';

    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }

    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }

    public function getIssuer()
    {
        return $this->getParameter('issuer');
    }

    public function setIssuer($value)
    {
        return $this->setParameter('issuer', $value);
    }

    protected function getBaseData()
    {
        $data = array();

        if ($this->getTestMode()) {
            $data['testmode'] = 'true';
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data)->send();

        return $this->response = new Response($this, $httpResponse->xml());
    }
}
