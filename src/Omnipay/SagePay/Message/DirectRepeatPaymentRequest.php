<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Purchase Request
 */
class DirectRepeatPaymentRequest extends AbstractRequest

{
    protected $action = 'REPEAT';
    
    protected function getBaseAuthorizeData()
    {
        $data = $this->getBaseData();
        
        return $data;
        
    }

    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        

        $data['Currency'] = 'GBP';
        $data['Amount'] = $this->getAmount();
        
        $data['Description'] = $this->getDescription();
        //Unique reference to THIS payment
        $data['VendorTxCode'] = $this->getTransactionId();
        
        //Specific to repeat payments
        //Return results from previous Payments
        $data['RelatedVPSTxId'] = $this->getRelatedVPSTxId();
        $data['RelatedVendorTxCode'] = $this->getRelatedTransactionId();
        $data['RelatedSecurityKey'] = $this->getRelatedSecurityKey();
        $data['RelatedTxAuthNo'] = $this->getRelatedTxAuthNo();
        
        // billing details
        return $data;
    }
    
  
    public function getDescription(){
        return $this->getParameter('description');
    }
    
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }
  
    public function getRelatedVPSTxId(){
        return $this->getParameter('relatedVPSTxId');
    }
    
    public function setRelatedVPSTxId($value)
    {
        return $this->setParameter('relatedVPSTxId', $value);
    }
    
    public function getRelatedTransactionId(){
        return $this->getParameter('relatedTransactionId');
    }
    
    public function setRelatedTransactionId($value)
    {
        return $this->setParameter('relatedTransactionId', $value);
    }
   
    public function getRelatedSecurityKey(){
        return $this->getParameter('relatedSecurityKey');
    }
    
    public function setRelatedSecurityKey($value)
    {
        return $this->setParameter('relatedSecurityKey', $value);
    }
   
    public function getRelatedTxAuthNo(){
        return $this->getParameter('relatedTxAuthNo');
    }
    
    public function setRelatedTxAuthNo($value)
    {
        return $this->setParameter('relatedTxAuthNo', $value);
    }
    
    
    public function getService()
    {
        return 'repeat';
    }

  } 