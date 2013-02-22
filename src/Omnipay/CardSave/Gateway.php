<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave;

use DOMDocument;
use Omnipay\CardSave\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * CardSave Gateway
 *
 * @link http://www.cardsave.net/dev-downloads
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://gw1.cardsaveonlinepayments.com:4430/';
    protected $merchantId;
    protected $password;

    public function getName()
    {
        return 'CardSave';
    }

    public function defineSettings()
    {
        return array(
            'merchantId' => '',
            'password' => '',
        );
    }

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

    public function purchase($options = null)
    {
        $request = new PurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completePurchase($options = null)
    {
        $request = new Request($options);
        $data = $this->build3DAuthRequest();

        return $this->send($data, $request);
    }

    public function send(RequestInterface $request)
    {
        // the PHP SOAP library sucks, and SimpleXML can't append element trees
        // TODO: find PSR-0 SOAP library
        $document = new DOMDocument('1.0', 'utf-8');
        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope')
        );
        $envelope->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $envelope->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $body = $envelope->appendChild($document->createElement('soap:Body'));
        $body->appendChild($document->importNode(dom_import_simplexml($request->getData()), true));

        // post to Cardsave
        $rootElement = $request->getData()->getName();
        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'https://www.thepaymentgateway.net/'.$rootElement);
        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();

        return $this->createResponse($request, $httpResponse->getBody());
    }
}
