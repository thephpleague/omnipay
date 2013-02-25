<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $this->validate(array('amount', 'card'));
        $this->getCard()->validate();

        $prefix = '';
        $data[$prefix.'PAYMENTACTION'] = $this->action;
        $data[$prefix.'AMT'] = $this->getAmountDecimal();
        $data[$prefix.'CURRENCYCODE'] = $this->getCurrency();
        $data[$prefix.'DESC'] = $this->getDescription();

        // add credit card details
        $data['CREDITCARDTYPE'] = $this->getCard()->getType();
        $data['ACCT'] = $this->getCard()->getNumber();
        $data['EXPDATE'] = $this->getCard()->getExpiryMonth().$this->getCard()->getExpiryYear();
        $data['STARTDATE'] = $this->getCard()->getStartMonth().$this->getCard()->getStartYear();
        $data['CVV2'] = $this->getCard()->getCvv();
        $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
        $data['IPADDRESS'] = '';
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
