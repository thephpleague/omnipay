<?php

namespace Omnipay\SagePay\Message;


/**
 * Sage Pay Direct Purchase Request
 */
class DirectCreateTokenRequest extends AbstractRequest

{
    protected $action = 'TOKEN';
    protected $cardBrandMap = array(
        'mastercard' => 'mc',
        'diners_club' => 'dc'
    );

    protected function getBaseAuthorizeData()
    {
        $data = $this->getBaseData();
        
        //$data['Currency'] = $this->getParameter['Currency'];
        
        $data['ClientIPAddress'] = $this->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting

       

        return $data;
        
    }

    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        $card = $this->getCard();

        $data['Currency'] = 'GBP';
        $data['CardNumber'] = $card->getNumber();
        $data['ExpiryDate'] = $this->getCard()->getExpiryDate('my');
        $data['CardType']   = $this->getCardBrand();
        $data['CardHolder'] = $card->getName();
        
        // billing details
        $data['BillingFirstnames'] = $card->getFirstName();
        $data['BillingSurname'] = $card->getLastName();
        $data['BillingAddress1'] = $card->getBillingAddress1();
        $data['BillingAddress2'] = $card->getBillingAddress2();
        $data['BillingCity'] = $card->getBillingCity();
        $data['BillingPostCode'] = $card->getBillingPostcode();
        $data['BillingState'] = $card->getBillingCountry() === 'US' ? $card->getBillingState() : null;
        $data['BillingCountry'] = $card->getBillingCountry();
        $data['BillingPhone'] = $card->getBillingPhone();

        // shipping details
        $data['DeliveryFirstnames'] = $card->getFirstName();
        $data['DeliverySurname'] = $card->getLastName();
        $data['DeliveryAddress1'] = $card->getShippingAddress1();
        $data['DeliveryAddress2'] = $card->getShippingAddress2();
        $data['DeliveryCity'] = $card->getShippingCity();
        $data['DeliveryPostCode'] = $card->getShippingPostcode();
        $data['DeliveryState'] = $card->getShippingCountry() === 'US' ? $card->getShippingState() : null;
        $data['DeliveryCountry'] = $card->getShippingCountry();
        $data['DeliveryPhone'] = $card->getShippingPhone();
        $data['CustomerEMail'] = $card->getEmail();
        
        
        if ($card->getStartMonth() and $card->getStartYear()) {
            $data['StartDate'] = $card->getStartDate('my');
        }
        if ($card->getIssueNumber()) {
            $data['IssueNumber'] = $card->getIssueNumber();
        }
        $data['CV2'] = $card->getCvv();


        return $data;
    }

    public function getService()
    {
        return 'directtoken';
    }

    protected function getCardBrand()
    {
        $brand = $this->getCard()->getBrand();

        if (isset($this->cardBrandMap[$brand])) {
            return $this->cardBrandMap[$brand];
        }

        return $brand;
    }
}
