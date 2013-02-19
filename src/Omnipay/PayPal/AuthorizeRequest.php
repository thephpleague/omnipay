<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

/**
 * PayPal Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $paymentAction;

    public function getPaymentAction()
    {
        return $this->paymentAction;
    }

    public function setPaymentAction($value)
    {
        $this->paymentAction = $value;

        return $this;
    }

    public function getData()
    {
        $data = $this->getBaseData('DoDirectPayment');

        $this->validate(array('amount', 'card'));
        $this->card->validate();

        $prefix = '';
        $data[$prefix.'PAYMENTACTION'] = $this->paymentAction;
        $data[$prefix.'AMT'] = $this->getAmountDecimal();
        $data[$prefix.'CURRENCYCODE'] = $this->getCurrency();
        $data[$prefix.'DESC'] = $this->getDescription();

        // add credit card details
        $data['CREDITCARDTYPE'] = $this->card->getType();
        $data['ACCT'] = $this->card->getNumber();
        $data['EXPDATE'] = $this->card->getExpiryMonth().$this->card->getExpiryYear();
        $data['STARTDATE'] = $this->card->getStartMonth().$this->card->getStartYear();
        $data['CVV2'] = $this->card->getCvv();
        $data['ISSUENUMBER'] = $this->card->getIssueNumber();
        $data['IPADDRESS'] = '';
        $data['FIRSTNAME'] = $this->card->getFirstName();
        $data['LASTNAME'] = $this->card->getLastName();
        $data['EMAIL'] = $this->card->getEmail();
        $data['STREET'] = $this->card->getAddress1();
        $data['STREET2'] = $this->card->getAddress2();
        $data['CITY'] = $this->card->getCity();
        $data['STATE'] = $this->card->getState();
        $data['ZIP'] = $this->card->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($this->card->getCountry());

        return $data;
    }
}
