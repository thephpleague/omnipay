<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;
use Omnipay\PayPal\Message\ProAuthorizeRequest;
use Omnipay\PayPal\Message\CaptureRequest;
use Omnipay\PayPal\Message\RefundRequest;

/**
 * PayPal Pro Class
 */
class ProGateway extends AbstractGateway
{
    protected $username;
    protected $password;
    protected $signature;
    protected $testMode;

    public function getName()
    {
        return 'PayPal Pro';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'signature' => '',
            'testMode' => false,
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

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($value)
    {
        $this->signature = $value;

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

    public function authorize(array $options = null)
    {
        $request = new ProAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new ProAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function capture(array $options = null)
    {
        $request = new CaptureRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function refund(array $options = null)
    {
        $request = new RefundRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
