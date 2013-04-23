<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net CIM Abstract Request
 */
abstract class CIMAbstractRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.authorize.net/xml/v1/request.api';
    protected $developerEndpoint = 'https://apitest.authorize.net/xml/v1/request.api';

    public function getCustomerEmail()
    {
        return $this->getParameter('customerEmail');
    }

    public function setCustomerEmail($value)
    {
        $this->setParameter('customerEmail', $value);
    }

    public function getCustomerProfileId()
    {
        return $this->getParameter('customerProfileId');
    }

    public function setCustomerProfileId($value)
    {
        $this->setParameter('customerProfileId', $value);
    }

    public function getCustomerPaymentProfileId()
    {
        return $this->getParameter('customerPaymentProfileId');
    }

    public function setCustomerPaymentProfileId($value)
    {
        $this->setParameter('customerPaymentProfileId', $value);
    }

    public function getRequestType()
    {
        if (!$this->getParameter('requestType')) {
            $this->setRequestType('createCustomerProfileRequest');
        }

        return $this->getParameter('requestType');
    }

    public function setRequestType($value)
    {
        $this->setParameter('requestType', $value);
    }

    protected function getBaseData()
    {
        $data = new \SimpleXMLElement(sprintf('<?xml version="1.0" encoding="utf-8"?><%s />', $this->getRequestType()));
        $data->addAttribute('xmlns', 'AnetApi/xml/v1/schema/AnetApiSchema.xsd');
        
        $data->merchantAuthentication->name = $this->getApiLoginId();
        $data->merchantAuthentication->transactionKey = $this->getTransactionKey();

        return $data;
    }

    protected function getBillingData()
    {
        $data = $this->getBaseData();
        if ($card = $this->getCard()) {
            $data->customerProfileId = $this->getCustomerProfileId();
            // customer billing details
            $paymentProfile = $data->addChild('paymentProfile');
            $billTo = $paymentProfile->addChild('billTo');
            $billTo->firstName = $card->getBillingFirstName();
            $billTo->lastName = $card->getBillingLastName();
            $billTo->company = $card->getBillingCompany();
            $billTo->address = trim(
                $card->getBillingAddress1()." \n".
                $card->getBillingAddress2()
            );
            $billTo->city = $card->getBillingCity();
            $billTo->state = $card->getBillingState();
            $billTo->zip = $card->getBillingPostcode();
            $billTo->country = $card->getBillingCountry();
            $billTo->phoneNumber = $card->getBillingPhone();

            // credit card details
            $payment = $paymentProfile->addChild('payment');
            $payment->creditCard->cardNumber = $card->getNumber();
            $payment->creditCard->expirationDate = $card->getExpiryDate('Y-m');
            $payment->creditCard->cardCode = $card->getCvv();

            $data->validationMode = 'liveMode';
        }

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post(
            $this->getEndpoint(), 
            array('Content-Type' => 'text/xml'),
            $this->getData()->asXML()
        )->send();

        return $this->response = new CIMResponse($this, $this->getXml($httpResponse));
    }

    public function getEndpoint()
    {
        return $this->getDeveloperMode() ? $this->developerEndpoint : $this->liveEndpoint;
    }

    /**
     * Parse the XML response body and return a SimpleXMLElement
     *
     * @return \SimpleXMLElement
     * @throws RuntimeException if the response body is not in XML format
     */
    public function getXml($httpResponse)
    {
        // cat not valid response xmlns which returned by Authorize.Net
        $body = str_replace('xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"', '', $httpResponse->getBody(true));

        try {
            // Allow XML to be retrieved even if there is no response body
            $xml = new \SimpleXMLElement((string) $body ?: '<root />');
        } catch (\Exception $e) {
            throw new RuntimeException('Unable to parse response body into XML: ' . $e->getMessage());
        }

        return $xml;
    }
}
