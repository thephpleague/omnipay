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
use Omnipay\PaymentExpress\Message\PxPostAuthorizeRequest;
use Omnipay\PaymentExpress\Message\PxPostCaptureRequest;
use Omnipay\PaymentExpress\Message\PxPostPurchaseRequest;
use Omnipay\PaymentExpress\Message\PxPostRefundRequest;

/**
 * DPS PaymentExpress PxPost Gateway
 */
class PxPostGateway extends AbstractGateway
{
    protected $username;
    protected $password;

    public function getName()
    {
        return 'PaymentExpress PxPost';
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

    public function authorize($options = null)
    {
        $request = new PxPostAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function capture($options = null)
    {
        $request = new PxPostCaptureRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase($options = null)
    {
        $request = new PxPostPurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function refund($options = null)
    {
        $request = new PxPostRefundRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
