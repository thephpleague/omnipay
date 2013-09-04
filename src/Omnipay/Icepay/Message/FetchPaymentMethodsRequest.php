<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use DOMDocument;
use SimpleXMLElement;

class FetchPaymentMethodsRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('merchantId', 'secretCode');

        $data = new SimpleXMLElement('<GetMyPaymentMethods/>');
        $request = $data->addChild('request');
        $request->addChild('Checksum', $this->generateSignature());
        $request->addChild('MerchantID', $this->getMerchantId());
        $request->addChild('Timestamp', $this->getTimestamp());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $data = $this->getData();

        // the PHP SOAP library sucks, and SimpleXML can't append element trees
        // TODO: find PSR-0 SOAP library
        $document = new DOMDocument('1.0', 'utf-8');
        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Envelope')
        );
        $envelope->setAttribute('xmlns:ns1', 'http://schemas.datacontract.org/2004/07/APIService');
        $envelope->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $envelope->setAttribute('xmlns:ns2', $this->namespace);
        $body = $envelope->appendChild($document->createElement('SOAP-ENV:Body'));
        $body->appendChild($document->importNode(dom_import_simplexml($data), true));

        // post to Icepay
        $httpResponse = $this->httpClient->post(
            $this->endpoint,
            array(
                'Content-Type' => 'text/xml; charset=utf-8',
                'SOAPAction' => $this->namespace.'/IICEPAY/GetMyPaymentMethods',
            ),
            $this->replaceWithNamespaced($document)
        )->send();

        // Can't work with SimpleXMLElement here due to malformed response body
        return $this->response = new FetchPaymentMethodsResponse($this, $httpResponse->getBody(true));
    }

    /**
     * {@inheritdoc}
     */
    protected function generateSignature()
    {
        $raw = implode(
            '|',
            array(
                $this->getMerchantId(),
                $this->getSecretCode(),
                $this->getTimestamp(),
            )
        );

        return sha1($raw);
    }

    /**
     * @param DOMDocument $document
     *
     * @return string
     *
     * @todo this is not the way to do this
     */
    private function replaceWithNamespaced(DOMDocument $document)
    {
        $xml = $document->saveXML();
        $xml = str_replace('GetMyPaymentMethods', 'ns2:GetMyPaymentMethods', $xml);
        $xml = str_replace('request', 'ns2:request', $xml);
        $xml = str_replace('MerchantID', 'ns1:MerchantID', $xml);
        $xml = str_replace('Timestamp', 'ns1:Timestamp', $xml);
        $xml = str_replace('Checksum', 'ns1:Checksum', $xml);

        return $xml;
    }
}
