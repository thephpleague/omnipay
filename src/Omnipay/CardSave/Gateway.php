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

use SimpleXMLElement;
use DOMDocument;
use Omnipay\Common\AbstractGateway;
use Omnipay\Exception;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\FormRedirectResponse;
use Omnipay\Common\Message\AbstractRequest;
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
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function purchase($options = null)
    {
        $request = new Request($options);
        $data = $this->buildPurchaseRequest($request);

        return $this->send($data, $request);
    }

    public function completePurchase($options = null)
    {
        $request = new Request($options);
        $data = $this->build3DAuthRequest();

        return $this->send($data, $request);
    }

    protected function buildPurchaseRequest(Request $request)
    {
        $request->validate(array('amount'));
        $source = $request->getCard();
        $source->validate();

        $data = $this->buildRequest('CardDetailsTransaction');
        $data->PaymentMessage->MerchantAuthentication['MerchantID'] = $this->merchantId;
        $data->PaymentMessage->MerchantAuthentication['Password'] = $this->password;
        $data->PaymentMessage->TransactionDetails['Amount'] = $request->getAmount();
        $data->PaymentMessage->TransactionDetails['CurrencyCode'] = $request->getCurrencyNumeric();
        $data->PaymentMessage->TransactionDetails->OrderID = $request->getTransactionId();
        $data->PaymentMessage->TransactionDetails->OrderDescription = $request->getDescription();
        $data->PaymentMessage->TransactionDetails->MessageDetails['TransactionType'] = 'SALE';
        $data->PaymentMessage->CardDetails->CardName = $source->getName();
        $data->PaymentMessage->CardDetails->CardNumber = $source->getNumber();
        $data->PaymentMessage->CardDetails->ExpiryDate['Month'] = $source->getExpiryDate('m');
        $data->PaymentMessage->CardDetails->ExpiryDate['Year'] = $source->getExpiryDate('y');
        $data->PaymentMessage->CardDetails->CV2 = $source->getCvv();

        if ($source->getIssueNumber()) {
            $data->PaymentMessage->CardDetails->IssueNumber = $source->getIssueNumber();
        }

        if ($source->getStartMonth() && $source->getStartYear()) {
            $data->PaymentMessage->CardDetails->StartDate['Month'] = $source->getStartDate('m');
            $data->PaymentMessage->CardDetails->StartDate['Year'] = $source->getStartDate('y');
        }

        $data->PaymentMessage->CustomerDetails->BillingAddress->Address1 = $source->getAddress1();
        $data->PaymentMessage->CustomerDetails->BillingAddress->Address2 = $source->getAddress2();
        $data->PaymentMessage->CustomerDetails->BillingAddress->City = $source->getCity();
        $data->PaymentMessage->CustomerDetails->BillingAddress->PostCode = $source->getPostcode();
        $data->PaymentMessage->CustomerDetails->BillingAddress->State = $source->getState();
        // requires numeric country code
        // $data->PaymentMessage->CustomerDetails->BillingAddress->CountryCode = $source->getCountryNumeric;
        $data->PaymentMessage->CustomerDetails->CustomerIPAddress = $request->getClientIp();

        return $data;
    }

    protected function build3DAuthRequest()
    {
        $md = $this->htttpRequest->get('MD');
        $paRes = $this->htttpRequest->get('PaRes');
        if (empty($md) || empty($paRes)) {
            throw new InvalidResponseException;
        }

        $data = $this->buildRequest('ThreeDSecureAuthentication');
        $data->ThreeDSecureMessage->MerchantAuthentication['MerchantID'] = $this->merchantId;
        $data->ThreeDSecureMessage->MerchantAuthentication['Password'] = $this->password;
        $data->ThreeDSecureMessage->ThreeDSecureInputData['CrossReference'] = $md;
        $data->ThreeDSecureMessage->ThreeDSecureInputData->PaRES = $paRes;

        return $data;
    }

    protected function buildRequest($action)
    {
        $data = new SimpleXMLElement("<$action/>");
        $data->addAttribute('xmlns', 'https://www.thepaymentgateway.net/');

        return $data;
    }

    public function send(RequestInterface $request)
    {
        throw new \BadMethodCallException('fixme');
    }

    protected function oldSend($data, Request $request)
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
        $body->appendChild($document->importNode(dom_import_simplexml($data), true));

        // post to Cardsave
        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'https://www.thepaymentgateway.net/'.$data->getName());
        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();

        // we only care about the content of the soap:Body element
        $responseDom = new DOMDocument;
        $responseDom->loadXML($httpResponse->getBody());
        $response = simplexml_import_dom($responseDom->documentElement->firstChild->firstChild);

        $resultElement = $data->getName().'Result';
        if (!isset($response->$resultElement->StatusCode)) {
            throw new InvalidResponseException;
        }

        $status = (int) $response->$resultElement->StatusCode;
        if (3 === $status) {
            // redirect for 3d authentication
            $redirectUrl = (string) $response->TransactionOutputData->ThreeDSecureOutputData->ACSURL;
            $redirectData = array(
                'PaReq' => (string) $response->TransactionOutputData->ThreeDSecureOutputData->PaREQ,
                'TermUrl' => $request->getReturnUrl(),
                'MD' => (string) $response->TransactionOutputData['CrossReference'],
            );

            return new FormRedirectResponse($redirectUrl, $redirectData);
        }

        return new Response($response);
    }
}
