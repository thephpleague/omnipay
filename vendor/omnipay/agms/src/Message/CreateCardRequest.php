<?php

namespace Omnipay\Agms\Message;

/**
 * Agms Create Card Request
 */
class CreateCardRequest extends AbstractRequest
{
    /**
     * Endpoint URL
     *
     * @var string URL
     */
    protected $endpoint = 'https://gateway.agms.com/roxapi/AGMS_SAFE_API.asmx';
    
    /**
     * Safe Action
     *
     * @var string
     */
    protected $safeAction = 'add_safe';

    /**
     * Get the request data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCard()->validate();
        $data = $this->getBaseData();
        $data['CCNumber'] = $this->getCard()->getNumber();
        $data['CCExpDate'] = $this->getCard()->getExpiryDate('my');
        $data['CVV'] = $this->getCard()->getCvv();
        
        // Add billing data
        $data = array_merge($data, $this->getBillingData());
        return $data;
    }

    /**
     * Send the request
     *
     * @return AbstractResponse
     */
    public function sendData($data)
    {
        $xml = $this->buildTokenRequest($data, 'AddToSAFE');
        
        $headers = array(
            'content-type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'https://gateway.agms.com/roxapi/AddToSAFE'
        );

        $httpResponse =  $this->httpClient->post($this->getEndpoint(), $headers, $xml)->send();
        return $this->response = new Response($this, $httpResponse->getBody(), 'AddToSAFE');
    }

    
    
}
