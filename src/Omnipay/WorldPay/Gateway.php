<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\WorldPay\Message\CompletePurchaseRequest;
use Omnipay\WorldPay\Message\PurchaseRequest;

/**
 * WorldPay Gateway
 *
 * @link http://www.worldpay.com/support/kb/bg/htmlredirect/rhtml.html
 */
class Gateway extends AbstractGateway
{
    protected $installationId;
    protected $secretWord;
    protected $callbackPassword;
    protected $testMode;

    public function getName()
    {
        return 'WorldPay';
    }

    public function defineSettings()
    {
        return array(
            'installationId' => '',
            'secretWord' => '',
            'callbackPassword' => '',
            'testMode' => false,
        );
    }

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

    public function purchase($options = null)
    {
        $request = new PurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completePurchase($options = null)
    {
        $request = new CompletePurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
