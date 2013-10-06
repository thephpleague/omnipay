<?php

namespace Omnipay\PayPal\Message;

/**
 * PayPal Pro Authorize Request
 */
class ProAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
        $data = $this->getBaseData('DoDirectPayment');

        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data['PAYMENTACTION'] = $this->action;
        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();

        // add credit card details
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['CREDITCARDTYPE'] = $this->getCard()->getBrand();
        $data['EXPDATE'] = $this->getCard()->getExpiryMonth().$this->getCard()->getExpiryYear();
        $data['STARTDATE'] = $this->getCard()->getStartMonth().$this->getCard()->getStartYear();
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
        $data['IPADDRESS'] = $this->getClientIp();
        $data['FIRSTNAME'] = $this->getCard()->getFirstName();
        $data['LASTNAME'] = $this->getCard()->getLastName();
        $data['EMAIL'] = $this->getCard()->getEmail();
        $data['STREET'] = $this->getCard()->getAddress1();
        $data['STREET2'] = $this->getCard()->getAddress2();
        $data['CITY'] = $this->getCard()->getCity();
        $data['STATE'] = $this->getCard()->getState();
        $data['ZIP'] = $this->getCard()->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($this->getCard()->getCountry());

        return $data;
    }
}
