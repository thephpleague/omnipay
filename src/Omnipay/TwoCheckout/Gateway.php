<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout;

use Omnipay\Common\AbstractGateway;
use Omnipay\TwoCheckout\Message\CompletePurchaseRequest;
use Omnipay\TwoCheckout\Message\PurchaseRequest;

/**
 * 2Checkout Gateway
 *
 * @link http://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 */
class Gateway extends AbstractGateway
{
    protected $accountNumber;
    protected $secretWord;
    protected $testMode;

    public function getName()
    {
        return '2Checkout';
    }

    public function defineSettings()
    {
        return array(
            'accountNumber' => '',
            'secretWord' => '',
            'testMode' => false,
        );
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function setAccountNumber($value)
    {
        $this->accountNumber = $value;

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
