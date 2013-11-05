<?php

namespace Omnipay\Adyen\Message;

/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse  
{
    private $parameters = array();
    private $response = array();
    
    public function __construct(array $parameters = array()){
        
        $this->parameters = $parameters;
        
        if(isset($this->parameters['authResult']) and $this->parameters['authResult'] == 'AUTHORISED'){
            $this->response = array(
                'status' => true,
                'success' => $this->parameters
            );
        }else{      
            $this->response = array(
                'status' => false,
                'error' => $this->parameters
            );          
        }
    }
    
    public function getResponse(){ return $this->response; }
    
    public function getResponseError(){ return $this->response; }
      
    public function isSuccessful(){ return $this->response['status']; }   
}