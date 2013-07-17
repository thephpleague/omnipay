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
 * CardSave Card Type Request
 */
class CardType extends AbstractRequest
{
    protected $endpoint = 'https://gw1.cardsaveonlinepayments.com:4430/';
    protected $namespace = 'https://www.thepaymentgateway.net/';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getData()
    {
        $data = new SimpleXMLElement('<GetCardType/>');
        $data->addAttribute('xmlns', $this->namespace);

        $data->GetCardTypeMessage->MerchantAuthentication['MerchantID'] = $this->getMerchantId();
        $data->GetCardTypeMessage->MerchantAuthentication['Password'] = $this->getPassword();
        $data->GetCardTypeMessage->CardNumber = $this->getCard()->getNumber();

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
