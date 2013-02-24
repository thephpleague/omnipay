<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Netaxept Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://epayment.bbs.no';
    protected $testEndpoint = 'https://epayment-test.bbs.no';
    protected $merchantId;
    protected $token;
    protected $testMode;

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;

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

    public function getData()
    {
        $this->validate(array('amount', 'currency', 'transactionId', 'returnUrl'));

        $data = array();
        $data['merchantId'] = $this->merchantId;
        $data['token'] = $this->token;
        $data['serviceType'] = 'B';
        $data['orderNumber'] = $this->getTransactionId();
        $data['currencyCode'] = $this->getCurrency();
        $data['amount'] = $this->getAmount();
        $data['redirectUrl'] = $this->getReturnUrl();

        if ($this->card) {
            $data['customerFirstName'] = $this->card->getFirstName();
            $data['customerLastName'] = $this->card->getLastName();
            $data['customerEmail'] = $this->card->getEmail();
            $data['customerPhoneNumber'] = $this->card->getPhone();
            $data['customerAddress1'] = $this->card->getAddress1();
            $data['customerAddress2'] = $this->card->getAddress2();
            $data['customerPostcode'] = $this->card->getPostcode();
            $data['customerTown'] = $this->card->getCity();
            $data['customerCountry'] = $this->card->getCountry();
        }

        return $data;
    }

    public function send()
    {
        $url = $this->getEndpoint().'/Netaxept/Register.aspx?';
        $httpResponse = $this->httpClient->get($url.http_build_query($this->getData()))->send();

        return $this->response = new Response($this, $httpResponse->xml());
    }

    public function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
