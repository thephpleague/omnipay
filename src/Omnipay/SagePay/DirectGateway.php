<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;

/**
 * Sage Pay Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    protected $endpoint = 'https://live.sagepay.com/gateway/service';
    protected $testEndpoint = 'https://test.sagepay.com/gateway/service';
    protected $simulatorEndpoint = 'https://test.sagepay.com/Simulator';

    protected $vendor;
    protected $testMode;
    protected $simulatorMode;

    public function getName()
    {
        return 'Sage Pay Direct';
    }

    public function defineSettings()
    {
        return array(
            'vendor' => '',
            'testMode' => false,
            'simulatorMode' => false,
        );
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($value)
    {
        $this->vendor = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function getSimulatorMode()
    {
        return $this->simulatorMode;
    }

    public function setSimulatorMode($value)
    {
        $this->simulatorMode = $value;
    }

    public function authorize($options = null)
    {
        $request = new Request($options);
        $data = $this->buildDirectAuthorizeOrPurchase($request, 'DEFERRED');

        return $this->send('DEFERRED', $data, $request);
    }

    public function completeAuthorize($options = null)
    {
        $request = new Request($options);
        $data = $this->buildCompleteDirect3D($request);

        return $this->send('direct3dcallback', $data, $request);
    }

    public function capture($options = null)
    {
        $request = new Request($options);
        $data = $this->buildCapture($request);

        return $this->send('RELEASE', $data, $request);
    }

    public function purchase($options = null)
    {
        $request = new Request($options);
        $data = $this->buildDirectAuthorizeOrPurchase($request, 'PAYMENT');

        return $this->send('PAYMENT', $data, $request);
    }

    /**
     * Only used for returning from Direct 3D Authentication
     */
    public function completePurchase($options = null)
    {
        $request = new Request($options);
        $data = $this->buildCompleteDirect3D($request);

        return $this->send('direct3dcallback', $data, $request);
    }

    public function refund($options = null)
    {
        $request = new Request($options);
        $data = $this->buildRefund($request);

        return $this->send('REFUND', $data, $request);
    }

    protected function buildAuthorizeOrPurchase(Request $request, $method)
    {
        $request->validate(array('transactionId', 'card'));

        $data = array();
        $data['VPSProtocol'] = '2.23';
        $data['TxType'] = $method;
        $data['Vendor'] = $this->vendor;
        $data['Description'] = $request->getDescription();
        $data['Amount'] = $request->getAmountDecimal();
        $data['Currency'] = $request->getCurrency();
        $data['VendorTxCode'] = $request->getTransactionId();
        $data['ClientIPAddress'] = $request->getClientIp();
        $data['ApplyAVSCV2'] = 0; // use account setting
        $data['Apply3DSecure'] = 0; // use account setting

        // billing details
        $card = $request->getCard();
        $data['BillingFirstnames'] = $card->getFirstName();
        $data['BillingSurname'] = $card->getLastName();
        $data['BillingAddress1'] = $card->getBillingAddress1();
        $data['BillingAddress2'] = $card->getBillingAddress2();
        $data['BillingCity'] = $card->getBillingCity();
        $data['BillingPostCode'] = $card->getBillingPostcode();
        $data['BillingState'] = $card->getBillingState();
        $data['BillingCountry'] = $card->getBillingCountry();
        $data['BillingPhone'] = $card->getBillingPhone();

        $data['DeliveryFirstnames'] = $card->getFirstName();
        $data['DeliverySurname'] = $card->getLastName();
        $data['DeliveryAddress1'] = $card->getShippingAddress1();
        $data['DeliveryAddress2'] = $card->getShippingAddress2();
        $data['DeliveryCity'] = $card->getShippingCity();
        $data['DeliveryPostCode'] = $card->getShippingPostcode();
        $data['DeliveryState'] = $card->getShippingState();
        $data['DeliveryCountry'] = $card->getShippingCountry();
        $data['DeliveryPhone'] = $card->getShippingPhone();
        $data['CustomerEMail'] = $card->getEmail();

        return $data;
    }

    protected function buildDirectAuthorizeOrPurchase(Request $request, $method)
    {
        $request->validate(array('amount', 'card'));

        $card = $request->getCard();
        $card->validate();

        $data = $this->buildAuthorizeOrPurchase($request, $method);

        $data['CardHolder'] = $card->getName();
        $data['CardNumber'] = $card->getNumber();
        $data['CV2'] = $card->getCvv();
        $data['ExpiryDate'] = $card->getExpiryDate('my');
        $data['CardType'] = $card->getType();

        if ($card->getStartMonth() and $card->getStartYear()) {
            $data['StartDate'] = $card->getStartDate('my');
        }

        if ($card->getIssueNumber()) {
            $data['IssueNumber'] = $card->getIssueNumber();
        }

        return $data;
    }

    protected function buildCompleteDirect3D(Request $request)
    {
        $data = array(
            'MD' => $this->httpRequest->request->get('MD'),
            'PARes' => $this->httpRequest->request->get('PaRes'), // inconsistent caps are intentional
        );

        if (empty($data['MD']) OR empty($data['PARes'])) {
            throw new InvalidResponseException;
        }

        return $data;
    }

    protected function buildCapture(Request $request)
    {
        $request->validate(array('amount', 'gatewayReference'));
        $reference = json_decode($request->getGatewayReference(), true);

        $data = array();
        $data['TxType'] = 'RELEASE';
        $data['VPSProtocol'] = '2.23';
        $data['Vendor'] = $this->vendor;
        $data['ReleaseAmount'] = $request->getAmountDecimal();
        $data['VendorTxCode'] = $reference['VendorTxCode'];
        $data['VPSTxId'] = $reference['VPSTxId'];
        $data['SecurityKey'] = $reference['SecurityKey'];
        $data['TxAuthNo'] = $reference['TxAuthNo'];

        return $data;
    }

    protected function buildRefund(Request $request)
    {
        $request->validate(array('amount', 'gatewayReference'));
        $reference = json_decode($request->getGatewayReference(), true);

        $data = array();
        $data['TxType'] = 'REFUND';
        $data['VPSProtocol'] = '2.23';
        $data['Vendor'] = $this->vendor;
        $data['Amount'] = $request->getAmountDecimal();
        $data['Currency'] = $request->getCurrency();
        $data['Description'] = $request->getDescription();
        $data['RelatedVendorTxCode'] = $reference['VendorTxCode'];
        $data['RelatedVPSTxId'] = $reference['VPSTxId'];
        $data['RelatedSecurityKey'] = $reference['SecurityKey'];
        $data['RelatedTxAuthNo'] = $reference['TxAuthNo'];

        // VendorTxCode must be unique for the refund
        $data['VendorTxCode'] = $request->getTransactionId();

        return $data;
    }

    public function send(RequestInterface $request)
    {
        throw new \BadMethodCallException('fixme');
    }

    protected function oldSend($service, $data, Request $request)
    {
        $url = $this->getCurrentEndpoint($service);
        $httpResponse = $this->httpClient->post($url, null, $data)->send();

        return Response::create($httpResponse->getBody(), $request);
    }

    protected function getCurrentEndpoint($service)
    {
        $service = strtolower($service);
        if ($service == 'payment' || $service == 'deferred') {
            $service = 'vspdirect-register';
        }

        if ($this->simulatorMode) {
            // hooray for consistency
            if ($service == 'vspdirect-register') {
                return $this->simulatorEndpoint.'/VSPDirectGateway.asp';
            } elseif ($service == 'vspserver-register') {
                return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=VendorRegisterTx';
            } elseif ($service == 'direct3dcallback') {
                return $this->simulatorEndpoint.'/VSPDirectCallback.asp';
            }

            return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=Vendor'.ucfirst($service).'Tx';
        }

        if ($this->testMode) {
            return $this->testEndpoint."/$service.vsp";
        }

        return $this->endpoint."/$service.vsp";
    }
}
