<?php

namespace Omnipay\Agms\Message;

/**
 * Agms Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * Transaction type
     *
     * @return string
     */
    protected $transactionType = 'auth';
    
    /**
     * Get the request data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'card');

        $this->getCard()->validate();
        $data = $this->getBaseData();
        $data['CCNumber'] = $this->getCard()->getNumber();
        $data['CCExpDate'] = $this->getCard()->getExpiryDate('my');
        $data['CVV'] = $this->getCard()->getCvv();
        
        // Add invoice data
        $data = array_merge($data, $this->getInvoiceData());
        // Add billing data
        $data = array_merge($data, $this->getBillingData());
        // Add shipping data
        $data = array_merge($data, $this->getShippingData());
        
        return $data;
    }
    
}
