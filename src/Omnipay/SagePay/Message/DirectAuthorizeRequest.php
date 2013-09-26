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
    protected $cardBrandMap = array(
        'mastercard' => 'mc',
        'diners_club' => 'dc'
    );

    protected function getBaseAuthorizeData()
    {
        $this->validate('amount', 'card', 'transactionId');

        $data = $this->getBaseData();
        $data['Description'] = $this->getDescription();
        $data['Amount'] = $this->getAmount();
        $data['Currency'] = $this->getCurrency();
        $data['VendorTxCode'] = $this->getTransactionId();
        $data['ClientIPAddress'] = $this->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting

        // billing details
        $data['BillingFirstnames'] = $this->getCard()->getFirstName();
        $data['BillingSurname'] = $this->getCard()->getLastName();
        $data['BillingAddress1'] = $this->getCard()->getBillingAddress1();
        $data['BillingAddress2'] = $this->getCard()->getBillingAddress2();
        $data['BillingCity'] = $this->getCard()->getBillingCity();
        $data['BillingPostCode'] = $this->getCard()->getBillingPostcode();
        $data['BillingState'] = $this->getCard()->getBillingState();
        $data['BillingCountry'] = $this->getCard()->getBillingCountry();
        $data['BillingPhone'] = $this->getCard()->getBillingPhone();

        // shipping details
        $data['DeliveryFirstnames'] = $this->getCard()->getFirstName();
        $data['DeliverySurname'] = $this->getCard()->getLastName();
        $data['DeliveryAddress1'] = $this->getCard()->getShippingAddress1();
        $data['DeliveryAddress2'] = $this->getCard()->getShippingAddress2();
        $data['DeliveryCity'] = $this->getCard()->getShippingCity();
        $data['DeliveryPostCode'] = $this->getCard()->getShippingPostcode();
        $data['DeliveryState'] = $this->getCard()->getShippingState();
        $data['DeliveryCountry'] = $this->getCard()->getShippingCountry();
        $data['DeliveryPhone'] = $this->getCard()->getShippingPhone();
        $data['CustomerEMail'] = $this->getCard()->getEmail();

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
