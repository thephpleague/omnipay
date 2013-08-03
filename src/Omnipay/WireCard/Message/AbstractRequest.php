<?php

namespace Omnipay\WireCard\Message;

/**
 * WireCard Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $init_endpoint = "https://checkout.wirecard.com/seamless/dataStorage/init";
    protected $endpoint = "";

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }


    abstract public function getEndpoint();

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function send()
    {
        $headers = $this->getHeaders(); 
        //print_r($headers); die;
        $xml     = $this->getXml();
        $toSend = $this->httpClient->post($this->endpoint, $headers, $xml);
        try {
            $httpResponse = $toSend->send();
        } 
        catch(Exception $e) {
            echo 'ok';
        } 
        $this->response = new Response($this, $httpResponse->xml());
        return $this->response;
    }

    protected function getHeaders()
    {
        
        $data = $this->getData();
        $username = $data['business_case_signature'];
        $password = $data['password'];
        $auth = sprintf("%s:%s", $username, $password);
        return [
            "Authorization: Basic ".
            trim(base64_encode($auth)),
            "Content-Type: text/xml",
        ];
        return [
            "Authorization: Basic " . $auth . "\n",
            "Content-Type: text/xml"
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
}

