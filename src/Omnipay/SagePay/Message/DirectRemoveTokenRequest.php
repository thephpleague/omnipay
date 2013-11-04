<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Remove Token Request
 */
class DirectRemoveTokenRequest extends AbstractRequest
{
    protected $action = 'REMOVETOKEN';
    
    protected function getBaseAuthorizeData()
    {
        $data = $this->getBaseData();

        return $data;
    }    
    
    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        //Add the token ID to remove
        $data['Token'] = $this->getParameter('cardReference');

        return $data;
    }
    
    public function getService()
    {
        return 'removetoken';
    }
}
