<?php

/**
 * Agms Abstract Request
 */
namespace Omnipay\Agms\Message;

/**
 * Agms Abstract Request
 *
 * This is the parent class for all Agms requests.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Endpoint URL
     *
     * @var string URL
     */
    protected $endpoint = 'https://gateway.agms.com/roxapi/agms.asmx';
    
    /**
     * Payment type
     *
     * @var string URL
     */
    protected $paymentType = 'creditcard';

    /**
     * Get the gateway endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get the gateway username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the gateway username
     *
     * @return AbstractRequest
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the gateway password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the gateway password
     *
     * @return AbstractRequest
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the gateway api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway api key
     *
     * @return AbstractRequest
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Get the gateway account number
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Set the gateway account number
     *
     * @return AbstractRequest
     */
    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * Get the developer mode
     *
     * @return string
     */
    public function getDeveloperMode()
    {
        return False;
    }

    /**
     * Set the gateway developer mode
     *
     * @return AbstractRequest
     */
    public function setDeveloperMode($value)
    {
        return $this->setParameter('developerMode', False);
    }

    /**
     * Get the gateway base data
     *
     * @return array
     */
    protected function getBaseData()
    {
        $data = array();
        $data['GatewayUserName'] = $this->getUsername();
        $data['GatewayPassword'] = $this->getPassword();
        $data['PaymentType'] = $this->paymentType;

        if(isset($this->transactionType))
        {
            $data['TransactionType'] = $this->transactionType;
        }
        if(isset($this->safeAction))
        {
            $data['SAFE_Action'] = $this->safeAction;
        }
        return $data;
    }

    /**
     * Get the billing data
     *
     * @return array
     */
    protected function getBillingData()
    {
        $data = array();
        if ($card = $this->getCard()) {
            // Customer billing details
            $data['FirstName'] = $card->getBillingFirstName();
            $data['LastName'] = $card->getBillingLastName();
            $data['Company'] = $card->getBillingCompany();
            $data['Address1'] = $card->getBillingAddress1();
            if ( $card->getBillingAddress2() )
                $data['Address2'] = $card->getBillingAddress2();
            
            $data['City'] = $card->getBillingCity();
            $data['State'] = $card->getBillingState();
            $data['Zip'] = $card->getBillingPostcode();
            $data['Country'] = $card->getBillingCountry();
            $data['Phone'] = $card->getBillingPhone();
            $data['Email'] = $card->getEmail();
        }
        return $data;
    }

    /**
     * Get the shipping data
     *
     * @return array
     */
    protected function getShippingData()
    {
        $data = array();
        // Customer shipping details
        if ($card = $this->getCard()) {
            // Customer shipping details
            if ( $card->getShippingFirstName() )
                $data['ShippingFirstName'] = $card->getShippingFirstName();
            if ( $card->getShippingLastName() )  
                $data['ShippingLastName'] = $card->getShippingLastName();
            if ( $card->getShippingCompany() )  
                $data['ShippingCompany'] = $card->getShippingCompany();
            if ( $card->getShippingAddress1() )  
                $data['ShippingAddress1'] = $card->getShippingAddress1();
            if ( $card->getShippingAddress2() )  
                $data['ShippingAddress2'] = $card->getShippingAddress2();
            if ( $card->getShippingCity() )  
                $data['ShippingCity'] = $card->getShippingCity();
            if ( $card->getShippingState() )  
                $data['ShippingState'] = $card->getShippingState();
            if ( $card->getShippingPostcode() )  
                $data['ShippingZip'] = $card->getShippingPostcode();
            if ( $card->getShippingCountry() )  
                $data['ShippingCountry'] = $card->getShippingCountry();
             
        }
        return $data;
    }

    /**
     * Get the invoice data
     *
     * @return array
     */
    protected function getInvoiceData()
    {
        $data = array();
        $data['Amount'] = $this->getAmount();
        if ( $this->getDescription() )
            $data['OrderDescription'] = $this->getDescription();
        return $data;
    }

    /**
     * Get the authorization data
     *
     * @return array
     */
    protected function getAuthData()
    {
        $data = array();
        if ( $this->getTransactionId() )
            $data['TransactionID'] = $this->getTransactionId();
        return $data;
    }

    /**
     * Send the request
     *
     * @return AbstractResponse
     */
    public function sendData($data)
    {
        $xml = $this->buildRequest($data);
        
        $headers = array(
            'content-type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'https://gateway.agms.com/roxapi/ProcessTransaction'
        );

        $httpResponse =  $this->httpClient->post($this->getEndpoint(), $headers, $xml)->send();
        return $this->response = new Response($this, $httpResponse->getBody());
    }

    /**
     * Build xml for the gateway
     *
     * @return xml string
     */
    protected function buildRequest($data){
        $xmlHeader = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ProcessTransaction xmlns="https://gateway.agms.com/roxapi/">
      <objparameters>';
        $xmlFooter = '</objparameters>
    </ProcessTransaction>
  </soap:Body>
</soap:Envelope>';
        $xmlBody = '';
        foreach ($data as $key => $value) {
            $xmlBody = $xmlBody . "<$key>$value</$key>";
        }
        return $xmlHeader . $xmlBody . $xmlFooter;
    }

    /**
     * Build xml for the gateway
     *
     * @return xml string
     */
    protected function buildTokenRequest($data, $op){
        $xmlHeader = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <' . $op . ' xmlns="https://gateway.agms.com/roxapi/">
      <vParameter>';
        $xmlFooter = '</vParameter>
    </' . $op . '>
  </soap:Body>
</soap:Envelope>';
        $xmlBody = '';
        foreach ($data as $key => $value) {
            $xmlBody = $xmlBody . "<$key>$value</$key>";
        }
        return $xmlHeader . $xmlBody . $xmlFooter;
    }
}
