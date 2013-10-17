<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * eWAY Direct Authorize Request
 */
class DirectAuthorizeRequest extends DirectAbstractRequest
{
    protected $liveEndpoint = 'https://www.eway.com.au/gateway_cvn/xmlauth.asp';
    protected $testEndpoint = 'https://www.eway.com.au/gateway_cvn/xmltest/authtestpage.asp';

    public function getData()
    {
        $this->validate('card');

        $xml = '<?xml version="1.0"?><ewaygateway></ewaygateway>';
        $sxml = new \SimpleXMLElement($xml);

        /* eWAY Customer Id */
        $sxml->addChild('ewayCustomerID', $this->getCustomerId());

        /* eWAY Transaction Details */
        $sxml->addChild('ewayTotalAmount', $this->getAmountInteger());
        $sxml->addChild('ewayTrxnNumber', $this->getTransactionId());

        /* Card Holder Details */
        $card = $this->getCard();
        $sxml->addChild('ewayCardHoldersName', $card->getName());
        $sxml->addChild('ewayCardNumber', $card->getNumber());
        $sxml->addChild('ewayCardExpiryMonth', $card->getExpiryDate('m'));
        $sxml->addChild('ewayCardExpiryYear', $card->getExpiryDate('y'));
        $sxml->addChild('ewayCVN', $card->getCVV());

        /* Customer Details */
        $sxml->addChild('ewayCustomerFirstName', $card->getFirstName());
        $sxml->addChild('ewayCustomerLastName', $card->getLastName());
        $sxml->addChild('ewayCustomerEmail', $card->getEmail());
        $sxml->addChild('ewayCustomerAddress', $card->getAddress1().' '.$card->getAddress2());
        $sxml->addChild('ewayCustomerPostcode', $card->getPostCode());

        $sxml->addChild('ewayOption1', $this->getOption1());
        $sxml->addChild('ewayOption2', $this->getOption2());
        $sxml->addChild('ewayOption3', $this->getOption3());

        $sxml->addChild('ewayCustomerInvoiceDescription', $this->getDescription());
        $sxml->addChild('ewayCustomerInvoiceRef', $this->getTransactionReference());

        return $sxml->asXML();
    }
}
