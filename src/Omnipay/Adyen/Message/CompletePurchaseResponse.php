<?php

namespace Omnipay\Adyen\Message;
use Omnipay\Common\Message\AbstractResponse;
/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    private $parameters = array();
    private $response = array();

    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;

        if (isset($this->parameters['authResult']) and $this->parameters['authResult'] == 'AUTHORISED') {
            $this->response = array(
                'status' => true,
                'success' => $this->parameters
            );
        } else {
            $this->response = array(
                'status' => false,
                'error' => $this->parameters
            );
        }
    }

    public function getResponse() { return $this->response; }

    public function getResponseError() { return $this->response; }

    public function isSuccessful() { 

        if($this->response['status'] === true){
        
            return $this->response['status']; 

        }
    }

}
