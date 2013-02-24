<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://secure.authorize.net/gateway/transact.dll';
    protected $developerEndpoint = 'https://test.authorize.net/gateway/transact.dll';
    protected $action;
    protected $apiLoginId;
    protected $transactionKey;
    protected $testMode;
    protected $developerMode;

    public function getApiLoginId()
    {
        return $this->apiLoginId;
    }

    public function setApiLoginId($value)
    {
        $this->apiLoginId = $value;

        return $this;
    }

    public function getTransactionKey()
    {
        return $this->transactionKey;
    }

    public function setTransactionKey($value)
    {
        $this->transactionKey = $value;

        return $this;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    public function getDeveloperMode()
    {
        return $this->developerMode;
    }

    public function setDeveloperMode($value)
    {
        $this->developerMode = $value;

        return $this;
    }

    protected function getBaseData()
    {
        $data = array();
        $data['x_login'] = $this->apiLoginId;
        $data['x_tran_key'] = $this->transactionKey;
        $data['x_type'] = $this->action;
        $data['x_version'] = '3.1';
        $data['x_delim_data'] = 'TRUE';
        $data['x_delim_char'] = ',';
        $data['x_encap_char'] = '|';
        $data['x_relay_response'] = 'FALSE';

        return $data;
    }

    protected function getBillingData()
    {
        $data = array();
        $data['x_amount'] = $this->getAmountDecimal();
        $data['x_invoice_num'] = $this->getTransactionId();
        $data['x_description'] = $this->getDescription();

        if ($this->card) {
            $data['x_first_name'] = $this->card->getFirstName();
            $data['x_last_name'] = $this->card->getLastName();
            $data['x_company'] = $this->card->getCompany();
            $data['x_address'] = trim($this->card->getAddress1()." \n".$this->card->getAddress2());
            $data['x_city'] = $this->card->getCity();
            $data['x_state'] = $this->card->getState();
            $data['x_zip'] = $this->card->getPostcode();
            $data['x_country'] = $this->card->getCountry();
            $data['x_phone'] = $this->card->getPhone();
            $data['x_email'] = $this->card->getEmail();
        }

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->get($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new AIMResponse($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->developerMode ? $this->developerEndpoint : $this->liveEndpoint;
    }
}
