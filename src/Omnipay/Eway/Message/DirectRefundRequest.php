<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * eWAY Direct Refund Request
 */
class DirectRefundRequest extends DirectAbstractRequest
{
    protected $liveEndpoint = 'https://www.eway.com.au/gateway/xmlpaymentrefund.asp';
    protected $testEndpoint = 'https://www.eway.com.au/gateway/xmltest/refund_test.asp';

    public function setRefundPassword($value)
    {
        return $this->setParameter('refundPassword', $value);
    }

    public function getRefundPassword()
    {
        return $this->getParameter('refundPassword');
    }

    public function getData()
    {
        $this->validate('refundPassword', 'transactionId');
        
        $xml = '<?xml version="1.0"?><ewaygateway></ewaygateway>';
        $sxml = new \SimpleXMLElement($xml);

        /* eWAY Customer Id */
        $sxml->addChild('ewayCustomerID', $this->getCustomerId());

        /* eWAY Transaction Details */
        $sxml->addChild('ewayTotalAmount', $this->getAmountInteger());
        $sxml->addChild('ewayOriginalTrxnNumber', $this->getTransactionId());

        /* Card Holder Details */
        $card = $this->getCard();
        $sxml->addChild('ewayCardExpiryMonth', $card->getExpiryDate('m'));
        $sxml->addChild('ewayCardExpiryYear', $card->getExpiryDate('y'));

        $sxml->addChild('ewayOption1', $this->getOption1());
        $sxml->addChild('ewayOption2', $this->getOption2());
        $sxml->addChild('ewayOption3', $this->getOption3());

        $sxml->addChild('ewayRefundPassword', $this->getRefundPassword());
        $sxml->addChild('ewayCustomerInvoiceRef', $this->getTransactionReference());

        return $sxml->asXML();
    }
}
