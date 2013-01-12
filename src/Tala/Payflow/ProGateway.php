<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payflow;

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

        $source->validateRequired(array('number', 'firstName', 'lastName', 'expiryMonth', 'expiryYear', 'cvv'));
        $source->validateNumber();

        $data = $this->buildRequest($action);
        $data['TENDER'] = 'C';
        $data['COMMENT1'] = $request->description;
        $data['ACCT'] = $source->number;
        $data['AMT'] = $request->amountDollars;
        $data['EXPDATE'] = $source->getExpiryDate('my');
        $data['CVV2'] = $source->cvv;
        $data['BILLTOFIRSTNAME'] = $source->firstName;
        $data['BILLTOLASTNAME'] = $source->lastName;
        $data['BILLTOSTREET'] = $source->address1;
        $data['BILLTOCITY'] = $source->city;
        $data['BILLTOSTATE'] = $source->state;
        $data['BILLTOZIP'] = $source->postcode;
        $data['BILLTOCOUNTRY'] = $source->country;

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
        $response = $this->getBrowser()->post($this->getCurrentEndpoint(), array(), http_build_query($data));

        return new Response($response->getContent());
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
