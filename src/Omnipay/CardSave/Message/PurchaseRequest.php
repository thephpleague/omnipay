<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * CardSave Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $namespace = 'https://www.thepaymentgateway.net/';
    protected $merchantId;
    protected $password;

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount', 'card'));
        $this->card->validate();

        $data = new SimpleXMLElement('<CardDetailsTransaction/>');
        $data->addAttribute('xmlns', $this->namespace);

        $data->PaymentMessage->MerchantAuthentication['MerchantID'] = $this->merchantId;
        $data->PaymentMessage->MerchantAuthentication['Password'] = $this->password;
        $data->PaymentMessage->TransactionDetails['Amount'] = $this->getAmount();
        $data->PaymentMessage->TransactionDetails['CurrencyCode'] = $this->getCurrencyNumeric();
        $data->PaymentMessage->TransactionDetails->OrderID = $this->getTransactionId();
        $data->PaymentMessage->TransactionDetails->OrderDescription = $this->getDescription();
        $data->PaymentMessage->TransactionDetails->MessageDetails['TransactionType'] = 'SALE';

        $data->PaymentMessage->CardDetails->CardName = $this->card->getName();
        $data->PaymentMessage->CardDetails->CardNumber = $this->card->getNumber();
        $data->PaymentMessage->CardDetails->ExpiryDate['Month'] = $this->card->getExpiryDate('m');
        $data->PaymentMessage->CardDetails->ExpiryDate['Year'] = $this->card->getExpiryDate('y');
        $data->PaymentMessage->CardDetails->CV2 = $this->card->getCvv();

        if ($this->card->getIssueNumber()) {
            $data->PaymentMessage->CardDetails->IssueNumber = $this->card->getIssueNumber();
        }

        if ($this->card->getStartMonth() && $this->card->getStartYear()) {
            $data->PaymentMessage->CardDetails->StartDate['Month'] = $this->card->getStartDate('m');
            $data->PaymentMessage->CardDetails->StartDate['Year'] = $this->card->getStartDate('y');
        }

        $data->PaymentMessage->CustomerDetails->BillingAddress->Address1 = $this->card->getAddress1();
        $data->PaymentMessage->CustomerDetails->BillingAddress->Address2 = $this->card->getAddress2();
        $data->PaymentMessage->CustomerDetails->BillingAddress->City = $this->card->getCity();
        $data->PaymentMessage->CustomerDetails->BillingAddress->PostCode = $this->card->getPostcode();
        $data->PaymentMessage->CustomerDetails->BillingAddress->State = $this->card->getState();
        // requires numeric country code
        // $data->PaymentMessage->CustomerDetails->BillingAddress->CountryCode = $this->card->getCountryNumeric;
        $data->PaymentMessage->CustomerDetails->CustomerIPAddress = $this->getClientIp();

        return $data;
    }

    public function createResponse($data)
    {
        return new Response($data);
    }
}
