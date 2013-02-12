<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Payflow;

use Omnipay\AbstractGateway;
use Omnipay\Request;

/**
 * Payflow Pro Class
 *
 * @link https://www.x.com/sites/default/files/payflowgateway_guide.pdf
 */
class ProGateway extends AbstractGateway
{
    protected $endpoint = 'https://payflowpro.paypal.com';
    protected $testEndpoint = 'https://pilot-payflowpro.paypal.com';
    protected $username;
    protected $password;
    protected $vendor;
    protected $partner;
    protected $testMode;

    public function getName()
    {
        return 'Payflow';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'vendor' => '',
            'partner' => '',
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

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($value)
    {
        $this->vendor = $value;
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function setPartner($value)
    {
        $this->partner = $value;
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
        $data = $this->buildAuthorize($options, 'A');

        return $this->send($data);
    }

    public function capture($options)
    {
        $data = $this->buildCaptureOrRefund($options, 'D');

        return $this->send($data);
    }

    public function purchase($options)
    {
        $data = $this->buildAuthorize($options, 'S');

        return $this->send($data);
    }

    public function refund($options)
    {

        $data = $this->buildCaptureOrRefund($options, 'C');

        return $this->send($data);
    }

    protected function buildAuthorize($options, $action)
    {
        $request = new Request($options);
        $request->validate(array('amount'));
        $source = $request->getCard();
        $source->validate();

        $data = $this->buildRequest($action);
        $data['TENDER'] = 'C';
        $data['COMMENT1'] = $request->getDescription();
        $data['ACCT'] = $source->getNumber();
        $data['AMT'] = $request->getAmountDollars();
        $data['EXPDATE'] = $source->getExpiryDate('my');
        $data['CVV2'] = $source->getCvv();
        $data['BILLTOFIRSTNAME'] = $source->getFirstName();
        $data['BILLTOLASTNAME'] = $source->getLastName();
        $data['BILLTOSTREET'] = $source->getAddress1();
        $data['BILLTOCITY'] = $source->getCity();
        $data['BILLTOSTATE'] = $source->getState();
        $data['BILLTOZIP'] = $source->getPostcode();
        $data['BILLTOCOUNTRY'] = $source->getCountry();

        return $data;
    }

    protected function buildCaptureOrRefund($options, $action)
    {
        $request = new Request($options);
        $request->validate(array('gatewayReference', 'amount'));

        $data = $this->buildRequest($action);
        $data['AMT'] = $request->getAmountDollars();
        $data['ORIGID'] = $request->getGatewayReference();

        return $data;
    }

    protected function buildRequest($action)
    {
        $request = array();
        $request['TRXTYPE'] = $action;
        $request['USER'] = $this->username;
        $request['PWD'] = $this->password;
        $request['VENDOR'] = $this->vendor;
        $request['PARTNER'] = $this->partner;

        return $request;
    }

    /**
     * Post a request to the Payflow API and decode the response
     */
    protected function send($data)
    {
        $response = $this->httpClient->post($this->getCurrentEndpoint(), $data);

        return new Response($response);
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
