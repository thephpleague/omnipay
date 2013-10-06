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
        $this->validate('amount', 'card', 'transactionId');
        $card = $this->getCard();

        $data = $this->getBaseData();
        $data['Description'] = $this->getDescription();
        $data['Amount'] = $this->getAmount();
        $data['Currency'] = $this->getCurrency();
        $data['VendorTxCode'] = $this->getTransactionId();
        $data['ClientIPAddress'] = $this->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting

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

        return $data;
    }

    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        $this->getCard()->validate();

        $data['CardHolder'] = $this->getCard()->getName();
        $data['CardNumber'] = $this->getCard()->getNumber();
        $data['CV2'] = $this->getCard()->getCvv();
        $data['ExpiryDate'] = $this->getCard()->getExpiryDate('my');
        $data['CardType'] = $this->getCardBrand();

        if ($this->getCard()->getStartMonth() and $this->getCard()->getStartYear()) {
            $data['StartDate'] = $this->getCard()->getStartDate('my');
        }

        if ($this->getCard()->getIssueNumber()) {
            $data['IssueNumber'] = $this->getCard()->getIssueNumber();
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
