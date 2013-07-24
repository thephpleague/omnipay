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
use DOMElement;
use DOMXPath;
use Omnipay\Common\Message\AbstractResponse;

class FetchPaymentMethodsResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        // TODO: handle error responses
        return true;
    }

    /**
     * Return available payment methods, and its issuers as
     * an associative array.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        // Avoid warning on malformed namespace
        $data = str_replace('connect.icepay.com', 'http://connect.icepay.com', $this->data);

        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadXML($data);

        $query = "/s:Envelope/s:Body/*[name()='GetMyPaymentMethodsResponse']/*[name()='GetMyPaymentMethodsResult']/a:PaymentMethods";
        $rawData = $this->getMainXPath($document)->query($query);

        $paymentMethods = array();
        foreach ($rawData as $item) {
            foreach ($item->childNodes as $paymentMethod) {
                $paymentMethodDocument = $this->documentFromElement($paymentMethod);
                $paymentMethods = array_merge($paymentMethods, $this->parsePaymentMethod($paymentMethodDocument));
            }
        }

        return $paymentMethods;
    }

    private function parsePaymentMethod(DOMDocument $document)
    {
        $rawData = $this->getSubXPath($document)->query("/b:PaymentMethod/*");

        $data = array();
        foreach ($rawData as $item) {
            if ('b:Issuers' === $item->nodeName) {
                $data['Issuers'] = $this->parseIssuers($this->documentFromElement($item));
                continue;
            }

            $data[str_replace('b:', '', $item->nodeName)] = $item->nodeValue;
        }

        return $this->processResult($data, 'PaymentMethodCode');
    }

    private function parseIssuers(DOMDocument $document)
    {
        $rawData = $this->getSubXPath($document)->query("/b:Issuers/*");

        $data = array();
        foreach ($rawData as $item) {
            $issuerDocument = $this->documentFromElement($item);
            $data = array_merge($data, $this->parseIssuer($issuerDocument));
        }

        return $data;
    }

    private function parseIssuer(DOMDocument $document)
    {
        $rawData = $this->getSubXPath($document)->query("/b:Issuer/*");

        $data = array();
        foreach ($rawData as $item) {
            if ('b:Countries' === $item->nodeName) {
                $data['Countries'] = $this->parseCountries($this->documentFromElement($item));
                continue;
            }

            $data[str_replace('b:', '', $item->nodeName)] = $item->nodeValue;
        }

        return $this->processResult($data, 'IssuerKeyword');
    }

    private function parseCountries(DOMDocument $document)
    {
        $rawData = $this->getSubXPath($document)->query("/b:Countries/*");

        $data = array();
        foreach ($rawData as $item) {
            $countryDocument = $this->documentFromElement($item);
            $data = array_merge($data, $this->parseCountry($countryDocument));
        }

        return $data;
    }

    private function parseCountry(DOMDocument $document)
    {
        $rawData = $this->getSubXPath($document)->query("/b:Country/*");

        $data = array();
        foreach ($rawData as $item) {
            $data[str_replace('b:', '', $item->nodeName)] = $item->nodeValue;
        }

        return $this->processResult($data, 'CountryCode');
    }

    /**
     * @param DOMDocument $document
     * @param array       $namespaces
     *
     * @return DOMXPath
     */
    private function getXPath(DOMDocument $document, array $namespaces = array())
    {
        $xpath = new DOMXPath($document);

        foreach ($namespaces as $prefix => $uri) {
            $xpath->registerNamespace($prefix, $uri);
        }

        return $xpath;
    }

    /**
     * @param DOMDocument $document
     *
     * @return DOMXPath
     */
    private function getMainXPath(DOMDocument $document)
    {
        return $this->getXPath($document, array(
            's' => 'http://schemas.xmlsoap.org/soap/envelope/',
            'a' => 'http://schemas.datacontract.org/2004/07/APIService',
        ));
    }

    /**
     * @param DOMDocument $document
     *
     * @return DOMXPath
     */
    private function getSubXPath(DOMDocument $document)
    {
        return $this->getXPath($document, array(
            'b' => 'http://schemas.datacontract.org/2004/07/APIService.Responses',
        ));
    }

    /**
     * @param DOMElement $element
     *
     * @return DOMDocument
     */
    private function documentFromElement(DOMElement $element)
    {
        $document = new DOMDocument('1.0', 'utf-8');
        $document->appendChild($document->importNode($element, true));

        return $document;
    }

    /**
     * @param array  $data
     * @param string $key
     *
     * @return array
     */
    private function processResult(array $data, $key)
    {
        $result = array();

        foreach ($data as $index => $value) {
            if ($index != $key) {
                $result[$data[$key]][$index] = $value;
            }
        }

        return $result;
    }
}
