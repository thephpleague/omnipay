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

use DOMDocument;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * CardSave Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://gw1.cardsaveonlinepayments.com:4430/';
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

    public function send()
    {
        $data = $this->getData();

        // the PHP SOAP library sucks, and SimpleXML can't append element trees
        // TODO: find PSR-0 SOAP library
        $document = new DOMDocument('1.0', 'utf-8');
        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope')
        );
        $envelope->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $envelope->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $body = $envelope->appendChild($document->createElement('soap:Body'));
        $body->appendChild($document->importNode(dom_import_simplexml($data), true));

        // post to Cardsave
        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => $this->namespace.$data->getName());

        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }
}
