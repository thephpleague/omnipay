<?php

namespace Omnipay\WireCard\Message;

use Omnipay\WireCard\Message\PurchaseResponse;

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
        $xml     = $this->getXml();
        $response =  $this->sendWithCurl($xml);

        /*
        $httpResponse = $this->httpClient
            ->post($this->endpoint, $headers, $xml)
            ->send();

        $this->response = new PurchaseResponse($this, $httpResponse->xml());
         */
        $this->response = new PurchaseResponse($this, $response);
        return $this->response;
    }

    public function sendWithCurl($post)
    {
        $header = $this->getHeaders();
        $url = $this->endpoint;
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_POST, 0);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        ob_start ();
        $result = curl_exec ($ch);
        ob_end_clean ();
    
        curl_close ($ch);
        return $result;
    }
 
    protected function getHeaders()
    {
        
        $data = $this->getData();
        $username = $data['business_case_signature'];
        $password = $data['password'];
        $auth = sprintf("%s:%s", $username, $password);
        return [
            "Authorization: Basic ". base64_encode($auth . "\n"),
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
}

