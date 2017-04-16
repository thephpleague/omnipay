<?php

namespace Omnipay\Agms\Message;

/**
 * Agms Delete Card Request
 */
class DeleteCardRequest extends AbstractRequest
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
    protected $safeAction = 'delete_safe';

    /**
     * Get the request data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('cardReference');
        $data = $this->getBaseData();
        $data['SAFE_ID'] = $this->getCardReference();
        return $data;
    }

    /**
     * Send the request
     *
     * @return AbstractResponse
     */
    public function sendData($data)
    {
        $xml = $this->buildTokenRequest($data, 'DeleteFromSAFE');
        
        $headers = array(
            'content-type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'https://gateway.agms.com/roxapi/DeleteFromSAFE'
        );

        $httpResponse =  $this->httpClient->post($this->getEndpoint(), $headers, $xml)->send();
        return $this->response = new Response($this, $httpResponse->getBody(), 'DeleteFromSAFE');
    }

    
}
