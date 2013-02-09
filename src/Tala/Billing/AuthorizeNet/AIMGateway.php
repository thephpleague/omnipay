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

    public function getName()
    {
        return 'Authorize.Net AIM';
    }

    public function defineSettings()
    {
        return array(
            'apiLoginId' => '',
            'transactionKey' => '',
            'testMode' => false,
            'developerMode' => false,
        );
    }

    public function getApiLoginId()
    {
        return $this->apiLoginId;
    }

    public function setApiLoginId($value)
    {
        $this->apiLoginId = $value;
    }

    public function getTransactionKey()
    {
        return $this->transactionKey;
    }

    public function setTransactionKey($value)
    {
        $this->transactionKey = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function getDeveloperMode()
    {
        return $this->developerMode;
    }

    public function setDeveloperMode($value)
    {
        $this->developerMode = $value;
    }

    public function authorize($options)
    {
        $data = $this->buildAuthorizeOrPurchase($options, 'AUTH_ONLY');

        return $this->send($data);
    }

    public function capture($options)
    {
        $data = $this->buildCapture($options);

        return $this->send($data);
    }

    public function purchase($options)
    {
        $data = $this->buildAuthorizeOrPurchase($options, 'AUTH_CAPTURE');

        return $this->send($data);
    }

    protected function buildAuthorizeOrPurchase($options, $method)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'card'));
        $source = $request->getCard();
        $source->validate();

        $data = $this->buildRequest($method);
        $data['x_customer_ip'] = $this->httpRequest->getClientIp();
        $data['x_card_num'] = $source->getNumber();
        $data['x_exp_date'] = $source->getExpiryDate('my');
        $data['x_card_code'] = $source->getCvv();

        if ($this->testMode) {
            $data['x_test_request'] = 'TRUE';
        }

        $this->addBillingDetails($request, $source, $data);

        return $data;
    }

    protected function buildCapture($options)
    {
        $request = new Request($options);
        $request->validate(array('amount', 'gatewayReference'));

        $data = $this->buildRequest('PRIOR_AUTH_CAPTURE');
        $data['x_amount'] = $request->getAmountDollars();
        $data['x_trans_id'] = $request->getGatewayReference();

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

    protected function addBillingDetails(Request $request, $source, &$data)
    {
        $data['x_amount'] = $request->getAmountDollars();
        $data['x_invoice_num'] = $request->getTransactionId();
        $data['x_description'] = $request->getDescription();

        if ($source) {
            $data['x_first_name'] = $source->getFirstName();
            $data['x_last_name'] = $source->getLastName();
            $data['x_company'] = $source->getCompany();
            $data['x_address'] = trim($source->getAddress1()." \n".$source->getAddress2());
            $data['x_city'] = $source->getCity();
            $data['x_state'] = $source->getState();
            $data['x_zip'] = $source->getPostcode();
            $data['x_country'] = $source->getCountry();
            $data['x_phone'] = $source->getPhone();
            $data['x_email'] = $source->getEmail();
        }
    }

    /**
     * Post a request to Authorize.Net
     */
    protected function send($data)
    {
        $response = $this->httpClient->post($this->getCurrentEndpoint(), $data);

        return new Response($response);
    }

    protected function getCurrentEndpoint()
    {
        return $this->developerMode ? $this->developerEndpoint : $this->endpoint;
    }
}
