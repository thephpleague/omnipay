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

        $data['PAYMENTACTION'] = $this->action;
        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();

        if ($this->getCardReference()) {
            $this->validate('amount');

            $data['METHOD'] = 'DoReferenceTransaction';
            $data['REFERENCEID'] = $this->getCardReference();

            // if card variables are set, override the original
            // transaction values
            if ($card = $this->getCard()) {
                if ($card->getNumber()) {
                    $data['ACCT'] = $card->getNumber();
                }
                if ($card->getBrand()) {
                    $data['CREDITCARDTYPE'] = $card->getBrand();
                }
                if ($card->getExpiryMonth() && $card->getExpiryYear()) {
                    $data['EXPDATE'] = $card->getExpiryDate('mY');
                }
                if ($card->getStartMonth() && $card->getStartYear()) {
                    $data['STARTDATE'] = $card->getStartMonth().$card->getStartYear();
                }
                if ($card->getCvv()) {
                    $data['CVV2'] = $card->getCvv();
                }
                if ($card->getIssueNumber()) {
                    $data['ISSUENUMBER'] = $card->getIssueNumber();
                }
                if ($card->getFirstName()) {
                    $data['FIRSTNAME'] = $card->getFirstName();
                }
                if ($card->getLastName()) {
                    $data['LASTNAME'] = $card->getLastName();
                }
                if ($card->getEmail()) {
                    $data['EMAIL'] = $card->getEmail();
                }
                if ($card->getAddress1()) {
                    $data['STREET'] = $card->getAddress1();
                }
                if ($card->getAddress2()) {
                    $data['STREET2'] = $card->getAddress2();
                }
                if ($card->getCity()) {
                    $data['CITY'] = $card->getCity();
                }
                if ($card->getState()) {
                    $data['STATE'] = $card->getState();
                }
                if ($card->getPostcode()) {
                    $data['ZIP'] = $card->getPostcode();
                }
                if ($card->getCountry()) {
                    $data['COUNTRYCODE'] = strtoupper($card->getCountry());
                }
            }
        } else {
            $this->validate('amount', 'card');
            $this->getCard()->validate();

            // add credit card details
            $data['ACCT'] = $this->getCard()->getNumber();
            $data['CREDITCARDTYPE'] = $this->getCard()->getBrand();
            $data['EXPDATE'] = $this->getCard()->getExpiryDate('mY');
            $data['STARTDATE'] = $this->getCard()->getStartMonth().$this->getCard()->getStartYear();
            $data['CVV2'] = $this->getCard()->getCvv();
            $data['ISSUENUMBER'] = $this->getCard()->getIssueNumber();
            $data['FIRSTNAME'] = $this->getCard()->getFirstName();
            $data['LASTNAME'] = $this->getCard()->getLastName();
            $data['EMAIL'] = $this->getCard()->getEmail();
            $data['STREET'] = $this->getCard()->getAddress1();
            $data['STREET2'] = $this->getCard()->getAddress2();
            $data['CITY'] = $this->getCard()->getCity();
            $data['STATE'] = $this->getCard()->getState();
            $data['ZIP'] = $this->getCard()->getPostcode();
            $data['COUNTRYCODE'] = strtoupper($this->getCard()->getCountry());
        }

        return $data;
    }
}
