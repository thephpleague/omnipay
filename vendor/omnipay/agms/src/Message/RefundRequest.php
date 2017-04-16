<?php

namespace Omnipay\Agms\Message;

/**
 * Agms Authorize Request
 */
class RefundRequest extends AbstractRequest
{
    /**
     * Transaction type
     *
     * @return string
     */
    protected $transactionType = 'refund';

    /**
     * Get the request data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'transactionId');
        $data = $this->getBaseData();

        // Add invoice data
        $data = array_merge($data, $this->getInvoiceData());
        // Add auth data
        $data = array_merge($data, $this->getAuthData());
        
        return $data;
    }

}
