<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\PayPal;

use Omnipay\AbstractGateway;
use Omnipay\Exception\InvalidResponseException;
use Omnipay\Request;

/**
 * PayPal Pro Class
 */
class ProGateway extends AbstractGateway
{
    protected $endpoint = 'https://api-3t.paypal.com/nvp';
    protected $testEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';
    protected $checkoutEndpoint = 'https://www.paypal.com/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/webscr';
    protected $username;
    protected $password;
    protected $signature;
    protected $testMode;

    public function getName()
    {
        return 'PayPal Pro';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'signature' => '',
            'testMode' => false,
        );
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($value)
    {
        $this->signature = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function authorize($options)
    {
        $data = $this->buildAuthorize($options, 'Authorization');
        $response = $this->send($data);

        return new Response($response);
    }

    public function purchase($options)
    {
        $data = $this->buildAuthorize($options, 'Sale');
        $response = $this->send($data);

        return new Response($response);
    }

    public function capture($options)
    {
        $data = $this->buildCapture($options);
        $response = $this->send($data);

        return new Response($response);
    }

    public function refund($options)
    {
        $request = $this->buildRefund($options);
        $response = $this->send($request);

        return new Response($response);
    }

    protected function buildAuthorize($options, $action)
    {
        $request = new Request($options);
        $request->validate(array('amount'));
        $source = $request->getCard();
        $source->validate();

        $data = $this->buildPaymentRequest($request, 'DoDirectPayment', $action);

        // add credit card details
        $data['CREDITCARDTYPE'] = $source->getType();
        $data['ACCT'] = $source->getNumber();
        $data['EXPDATE'] = $source->getExpiryMonth().$source->getExpiryYear();
        $data['STARTDATE'] = $source->getStartMonth().$source->getStartYear();
        $data['CVV2'] = $source->getCvv();
        $data['ISSUENUMBER'] = $source->getIssue();
        $data['IPADDRESS'] = '';
        $data['FIRSTNAME'] = $source->getFirstName();
        $data['LASTNAME'] = $source->getLastName();
        $data['EMAIL'] = $source->getEmail();
        $data['STREET'] = $source->getAddress1();
        $data['STREET2'] = $source->getAddress2();
        $data['CITY'] = $source->getCity();
        $data['STATE'] = $source->getState();
        $data['ZIP'] = $source->getPostcode();
        $data['COUNTRYCODE'] = strtoupper($source->getCountry());

        return $data;
    }

    protected function buildCapture($options)
    {
        $request = new Request($options);
        $request->validate(array('gatewayReference', 'amount'));

        $data = $this->buildRequest('DoCapture');
        $data['AMT'] = $request->getAmountDecimal();
        $data['CURRENCYCODE'] = $request->getCurrency();
        $data['AUTHORIZATIONID'] = $request->getGatewayReference();
        $data['COMPLETETYPE'] = 'Complete';

        return $data;
    }

    protected function buildRefund($options)
    {
        $request = new Request($options);
        $request->validate(array('gatewayReference'));

        $data = $this->buildRequest('RefundTransaction');
        $data['TRANSACTIONID'] = $request->getGatewayReference();
        $data['REFUNDTYPE'] = 'Full';

        return $data;
    }

    protected function buildRequest($method)
    {
        $data = array();
        $data['METHOD'] = $method;
        $data['VERSION'] = '85.0';
        $data['USER'] = $this->getUsername();
        $data['PWD'] = $this->getPassword();
        $data['SIGNATURE'] = $this->getSignature();

        return $data;
    }

    protected function buildPaymentRequest($request, $method, $action, $prefix = '')
    {
        $data = $this->buildRequest($method);

        $data[$prefix.'PAYMENTACTION'] = $action;
        $data[$prefix.'AMT'] = $request->getAmountDecimal();
        $data[$prefix.'CURRENCYCODE'] = $request->getCurrency();
        $data[$prefix.'DESC'] = $request->getDescription();

        return $data;
    }

    /**
     * Post a request to the PayPal API and decode the response
     */
    protected function send($data)
    {
        // send and decode response
        $response = $this->httpClient->get($this->getCurrentEndpoint().'?'.http_build_query($data));

        $response_vars = array();
        parse_str($response, $response_vars);

        // check whether response was successful
        if (isset($response_vars['ACK']) and in_array($response_vars['ACK'], array('Success', 'SuccessWithWarning'))) {
            return $response_vars;
        } elseif (isset($response_vars['L_LONGMESSAGE0'])) {
            throw new Exception($response_vars);
        }

        throw new InvalidResponseException();
    }

    protected function getCurrentEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->endpoint;
    }

    protected function getCurrentCheckoutEndpoint()
    {
        return $this->getTestMode() ? $this->testCheckoutEndpoint : $this->checkoutEndpoint;
    }
}
