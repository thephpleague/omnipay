<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPay Authorize Request
 */
class PxPayAuthorizeRequest extends AbstractRequest
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxpay/pxaccess.aspx';
    protected $action = 'Auth';
    protected $username;
    protected $password;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount', 'returnUrl'));

        $data = new SimpleXMLElement('<GenerateRequest/>');
        $data->PxPayUserId = $this->username;
        $data->PxPayKey = $this->password;
        $data->TxnType = $this->action;
        $data->AmountInput = $this->getAmountDecimal();
        $data->CurrencyInput = $this->getCurrency();
        $data->MerchantReference = $this->getDescription();
        $data->UrlSuccess = $this->getReturnUrl();
        $data->UrlFail = $this->getReturnUrl();

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $this->getData()->asXML())->send();

        return $this->createResponse($httpResponse->xml());
    }

    protected function createResponse($data)
    {
        return $this->response = new PxPayAuthorizeResponse($this, $data);
    }
}
