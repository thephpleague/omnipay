<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Direct Authorize Request
 */
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'DEFERRED';

    protected function getBaseAuthorizeData()
    {
        $this->validate(array('amount', 'card', 'transactionId'));

        $data = $this->getBaseData();
        $data['Description'] = $this->getDescription();
        $data['Amount'] = $this->getAmountDecimal();
        $data['Currency'] = $this->getCurrency();
        $data['VendorTxCode'] = $this->getTransactionId();
        $data['ClientIPAddress'] = $this->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting

        // billing details
        $data['BillingFirstnames'] = $this->card->getFirstName();
        $data['BillingSurname'] = $this->card->getLastName();
        $data['BillingAddress1'] = $this->card->getBillingAddress1();
        $data['BillingAddress2'] = $this->card->getBillingAddress2();
        $data['BillingCity'] = $this->card->getBillingCity();
        $data['BillingPostCode'] = $this->card->getBillingPostcode();
        $data['BillingState'] = $this->card->getBillingState();
        $data['BillingCountry'] = $this->card->getBillingCountry();
        $data['BillingPhone'] = $this->card->getBillingPhone();

        // shipping details
        $data['DeliveryFirstnames'] = $this->card->getFirstName();
        $data['DeliverySurname'] = $this->card->getLastName();
        $data['DeliveryAddress1'] = $this->card->getShippingAddress1();
        $data['DeliveryAddress2'] = $this->card->getShippingAddress2();
        $data['DeliveryCity'] = $this->card->getShippingCity();
        $data['DeliveryPostCode'] = $this->card->getShippingPostcode();
        $data['DeliveryState'] = $this->card->getShippingState();
        $data['DeliveryCountry'] = $this->card->getShippingCountry();
        $data['DeliveryPhone'] = $this->card->getShippingPhone();
        $data['CustomerEMail'] = $this->card->getEmail();

        return $data;
    }

    public function getData()
    {
        $data = $this->getBaseAuthorizeData();
        $this->card->validate();

        $data['CardHolder'] = $this->card->getName();
        $data['CardNumber'] = $this->card->getNumber();
        $data['CV2'] = $this->card->getCvv();
        $data['ExpiryDate'] = $this->card->getExpiryDate('my');
        $data['CardType'] = $this->card->getType();

        if ($this->card->getStartMonth() and $this->card->getStartYear()) {
            $data['StartDate'] = $this->card->getStartDate('my');
        }

        if ($this->card->getIssueNumber()) {
            $data['IssueNumber'] = $this->card->getIssueNumber();
        }

        return $data;
    }

    public function getService()
    {
        return 'vspdirect-register';
    }
}
