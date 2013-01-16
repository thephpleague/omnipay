<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\AuthorizeNet;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * Authorize.Net AIM Class
 */
class AIMGateway extends AbstractGateway
{
    protected $endpoint = 'https://secure.authorize.net/gateway/transact.dll';
    protected $developerEndpoint = 'https://test.authorize.net/gateway/transact.dll';

    protected $apiLoginId;
    protected $transactionKey;
    protected $testMode;
    protected $developerMode;

    public function getDefaultSettings()
    {
        return array(
            'apiLoginId' => '',
            'transactionKey' => '',
            'testMode' => false,
            'developerMode' => false,
        );
    }

    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'AUTH_ONLY');

        return $this->send($data);
    }

    public function capture(Request $request)
    {
        $data = $this->buildCapture($request);

        return $this->send($data);
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildAuthorizeOrPurchase($request, $source, 'AUTH_CAPTURE');

        return $this->send($data);
    }

    protected function buildAuthorizeOrPurchase($request, $source, $method)
    {
        $request->validateRequired('amount');

        $source->validateRequired(array('number', 'firstName', 'lastName', 'expiryMonth', 'expiryYear', 'cvv'));
        $source->validateNumber;

        $data = $this->buildRequest($method);
        $data['x_customer_ip'] = $this->getHttpRequest()->getClientIp();
        $data['x_card_num'] = $source->number;
        $data['x_exp_date'] = $source->getExpiryDate('my');
        $data['x_card_code'] = $source->cvv;

        if ($this->testMode) {
            $data['x_test_request'] = 'TRUE';
        }

        $this->addBillingDetails($request, $source, $data);

        return $data;
    }

    protected function buildCapture($request)
    {
        $request->validateRequired(array('gatewayReference', 'amount'));

        $data = $this->buildRequest('PRIOR_AUTH_CAPTURE');
        $data['x_amount'] = $request->amountDollars;
        $data['x_trans_id'] = $request->gatewayReference;

        return $data;
    }

    protected function buildRequest($method)
    {
        $data = array();
        $data['x_login'] = $this->apiLoginId;
        $data['x_tran_key'] = $this->transactionKey;
        $data['x_type'] = $method;
        $data['x_version'] = '3.1';
        $data['x_delim_data'] = 'TRUE';
        $data['x_delim_char'] = ',';
        $data['x_encap_char'] = '|';
        $data['x_relay_response'] = 'FALSE';

        return $data;
    }

    protected function addBillingDetails($request, $source, &$data)
    {
        $data['x_amount'] = $request->amountDollars;
        $data['x_invoice_num'] = $request->invoiceId;
        $data['x_description'] = $request->description;
        $data['x_first_name'] = $source->firstName;
        $data['x_last_name'] = $source->lastName;
        $data['x_company'] = $source->company;
        $data['x_address'] = trim($source->address1." \n".$source->address2);
        $data['x_city'] = $source->city;
        $data['x_state'] = $source->state;
        $data['x_zip'] = $source->postcode;
        $data['x_country'] = $source->country;
        $data['x_phone'] = $source->phone;
        $data['x_email'] = $source->email;
    }

    /**
     * Post a request to Authorize.Net
     */
    protected function send($data)
    {
        $response = $this->getHttpClient()->post($this->getCurrentEndpoint(), $data);

        return new Response($response);
    }

    protected function getCurrentEndpoint()
    {
        return $this->developerMode ? $this->developerEndpoint : $this->endpoint;
    }
}
