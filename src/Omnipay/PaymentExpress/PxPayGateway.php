<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress;

use Omnipay\Common\AbstractGateway;
use Omnipay\PaymentExpress\Message\PxPayAuthorizeRequest;
use Omnipay\PaymentExpress\Message\PxPayCompleteAuthorizeRequest;
use Omnipay\PaymentExpress\Message\PxPayPurchaseRequest;

/**
 * DPS PaymentExpress PxPay Gateway
 */
class PxPayGateway extends AbstractGateway
{
    protected $username;
    protected $password;

    public function getName()
    {
        return 'PaymentExpress PxPay';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
        );
    }

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

    public function authorize(array $options = null)
    {
        $request = new PxPayAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completeAuthorize(array $options = null)
    {
        $request = new PxPayCompleteAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new PxPayPurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completePurchase(array $options = null)
    {
        return $this->completeAuthorize($options);
    }
}
