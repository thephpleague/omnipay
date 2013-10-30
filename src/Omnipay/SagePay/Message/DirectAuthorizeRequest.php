<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Authorize Request
 */
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'DEFERRED';
    protected $cardBrandMap = array(
        'mastercard' => 'mc',
        'diners_club' => 'dc'
    );

    protected function getBaseAuthorizeData()
    {
        $this->validate('amount', 'transactionId');
        $data = $this->getBaseData();
        $data['Description'] = $this->getDescription();
        $data['Amount'] = $this->getAmount();
        $data['Currency'] = $this->getCurrency();
        $data['VendorTxCode'] = $this->getTransactionId();
        $data['ClientIPAddress'] = $this->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting
        
        if(!$this->getCardReference()){
            $card = $this->getCard();
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
        }else{
            //Card hasnt been sent so Values need to come from parameteres
            $data['BillingFirstnames'] = $this->getFirstName();
            $data['BillingSurname'] = $this->getLastName();
            $data['BillingAddress1'] = $this->getBillingAddress1();
            $data['BillingAddress2'] = $this->getBillingAddress2();
            $data['BillingCity'] = $this->getBillingCity();
            $data['BillingPostCode'] = $this->getBillingPostcode();
            $data['BillingState'] = $this->getBillingCountry() === 'US' ? $this->getBillingState() : null;
            $data['BillingCountry'] = $this->getBillingCountry();
            $data['BillingPhone'] = $this->getBillingPhone();

            // shipping details
            $data['DeliveryFirstnames'] = $this->getFirstName();
            $data['DeliverySurname'] = $this->getLastName();
            $data['DeliveryAddress1'] = $this->getShippingAddress1();
            $data['DeliveryAddress2'] = $this->getShippingAddress2();
            $data['DeliveryCity'] = $this->getShippingCity();
            $data['DeliveryPostCode'] = $this->getShippingPostcode();
            $data['DeliveryState'] = $this->getShippingCountry() === 'US' ? $this->getShippingState() : null;
            $data['DeliveryCountry'] = $this->getShippingCountry();
            $data['DeliveryPhone'] = $this->getShippingPhone();
            $data['CustomerEMail'] = $this->getEmail();
            
        }
        return $data;
    }

    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        
        //If this is a Token payment, add the Token data item, otherwise its a normal card purchase.
        if($this->getCardReference()){
            $data['Token']      = $this->getCardReference();
            $data['CV2']        =   $this->getParameter('cvv');
            $data['StoreToken'] = 1;
            
        }else{
            $this->getCard()->validate();

            $data['CardHolder'] = $this->getCard()->getName();
            $data['CardNumber'] = $this->getCard()->getNumber();
            $data['ExpiryDate'] = $this->getCard()->getExpiryDate('my');
            $data['CV2'] = $this->getCard()->getCvv();
            $data['CardType'] = $this->getCardBrand();
            
            if ($this->getCard()->getStartMonth() and $this->getCard()->getStartYear()) {
                $data['StartDate'] = $this->getCard()->getStartDate('my');
            }

            if ($this->getCard()->getIssueNumber()) {
                $data['IssueNumber'] = $this->getCard()->getIssueNumber();
            }

        }

        
        
        
        return $data;
    }

    public function getService()
    {
        return 'vspdirect-register';
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
