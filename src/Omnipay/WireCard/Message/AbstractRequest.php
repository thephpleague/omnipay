<?php

namespace Omnipay\WireCard\Message;

use Omnipay\WireCard\Message\PurchaseResponse;

/**
 * WireCard Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://c3-test.wirecard.com/secure/ssl-gateway';
    protected $signature = '56501';

    public function getCountryCode()
    {
        $list = [
           'Spain'   => 'ES',
           'England' => 'UK',
           'France'  => 'FR',
        ];
        $country = $this->getCard()->getCountry();
        return $list[$country];
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndPoint()
    {
        return $this->endpoint;
    }

    public function getCardNumber()
    {
        return $this->getCard()->getNumber();
    }

    public function send()
    {
        $this->response = new PurchaseResponse($this, $this->sendWithCurl());
        return $this->response;
        /*
        $headers      = $this->getHeaders();
        $httpResponse = $this->httpClient->post(
                            $this->endpoint, 
                            $this->getHeaders(), 
                            $this->getData()->asXml()
                        )->send();

        $this->response = new PurchaseResponse($this, $httpResponse);
        return $this->response;
         */
    }

    protected function getHeaders()
    {
        
        $username = $this->getSignature();
        $password = $this->getPassword();
        $auth = sprintf("%s:%s\n", $username, $password);
        return [
            "Authorization: Basic ". base64_encode($auth),
            "Content-Type: text/xml",
        ];
    }

    protected function getCardData()
    {
        $this->getCard()->validate();
        $data = array();
        $data['number'] = $this->getCard()->getNumber();
        $data['exp_month'] = $this->getCard()->getExpiryMonth();
        $data['exp_year'] = $this->getCard()->getExpiryYear();
        $data['cvc'] = $this->getCard()->getCvv();
        $data['name'] = $this->getCard()->getName();
        $data['address_line1'] = $this->getCard()->getAddress1();
        $data['address_line2'] = $this->getCard()->getAddress2();
        $data['address_city'] = $this->getCard()->getCity();
        $data['address_zip'] = $this->getCard()->getPostcode();
        $data['address_state'] = $this->getCard()->getState();
        $data['address_country'] = $this->getCard()->getCountry();
        return $data;
    }

    public function sendWithCurl()
    {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_POST, 0);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $this->getData()->asXml());
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        ob_start ();
        $result = curl_exec ($ch);
        ob_end_clean ();
        curl_close ($ch);
        return new \SimpleXmlElement($result);
    }
 
}

