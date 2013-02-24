<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * WorldPay Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://secure.worldpay.com/wcc/purchase';
    protected $testEndpoint = 'https://secure-test.worldpay.com/wcc/purchase';
    protected $installationId;
    protected $secretWord;
    protected $callbackPassword;
    protected $testMode;

    public function getInstallationId()
    {
        return $this->installationId;
    }

    public function setInstallationId($value)
    {
        $this->installationId = $value;

        return $this;
    }

    public function getSecretWord()
    {
        return $this->secretWord;
    }

    public function setSecretWord($value)
    {
        $this->secretWord = $value;

        return $this;
    }

    public function getCallbackPassword()
    {
        return $this->callbackPassword;
    }

    public function setCallbackPassword($value)
    {
        $this->callbackPassword = $value;

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
        $this->validate(array('amount', 'returnUrl'));

        $data = array();
        $data['instId'] = $this->installationId;
        $data['cartId'] = $this->getTransactionId();
        $data['desc'] = $this->getDescription();
        $data['amount'] = $this->getAmountDecimal();
        $data['currency'] = $this->getCurrency();
        $data['testMode'] = $this->testMode ? 100 : 0;
        $data['MC_callback'] = $this->getReturnUrl();

        if ($this->card) {
            $data['name'] = $this->card->getName();
            $data['address1'] = $this->card->getAddress1();
            $data['address2'] = $this->card->getAddress2();
            $data['town'] = $this->card->getCity();
            $data['region'] = $this->card->getState();
            $data['postcode'] = $this->card->getPostcode();
            $data['country'] = $this->card->getCountry();
            $data['tel'] = $this->card->getPhone();
            $data['email'] = $this->card->getEmail();
        }

        if ($this->secretWord) {
            $data['signatureFields'] = 'instId:amount:currency:cartId';
            $signature_data = array($this->secretWord,
                $data['instId'], $data['amount'], $data['currency'], $data['cartId']);
            $data['signature'] = md5(implode(':', $signature_data));
        }

        return $data;
    }

    public function createResponse($data)
    {
        $response = new PurchaseResponse($data);

        return $response->setEndpoint($this->getEndpoint());
    }

    protected function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
