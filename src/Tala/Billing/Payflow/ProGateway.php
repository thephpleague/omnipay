<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Payflow;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * Payflow Pro Class
 *
 * @link https://www.x.com/sites/default/files/payflowgateway_guide.pdf
 */
class ProGateway extends AbstractGateway
{
    protected $endpoint = 'https://payflowpro.paypal.com';
    protected $testEndpoint = 'https://pilot-payflowpro.paypal.com';

    public function getDefaultSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'vendor' => '',
            'partner' => '',
            'testMode' => false,
        );
    }

    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorize($request, $source, 'A');

        return $this->send($data);
    }

    public function capture(Request $request)
    {
        $data = $this->buildCaptureOrRefund($request, 'D');

        return $this->send($data);
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildAuthorize($request, $source, 'S');

        return $this->send($data);
    }

    public function refund(Request $request)
    {

        $data = $this->buildCaptureOrRefund($request, 'C');

        return $this->send($data);
    }

    protected function buildAuthorize(Request $request, $source, $action)
    {
        $request->validateRequired('amount');
        $source->validate();

        $data = $this->buildRequest($action);
        $data['TENDER'] = 'C';
        $data['COMMENT1'] = $request->description;
        $data['ACCT'] = $source->getNumber();
        $data['AMT'] = $request->amountDollars;
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

    protected function buildCaptureOrRefund(Request $request, $action)
    {
        $request->validateRequired(array('gatewayReference', 'amount'));

        $data = $this->buildRequest($action);
        $data['AMT'] = $request->amountDollars;
        $data['ORIGID'] = $request->gatewayReference;

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
