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
use Omnipay\Common\Request;
use Omnipay\Common\RequestInterface;

/**
 * PayPal Pro Class
 */
class ProGateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://api-3t.paypal.com/nvp';
    protected $testEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';
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

    public function authorize($options = null)
    {
        $request = new AuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setPaymentAction('Authorization');
    }

    public function purchase($options = null)
    {
        $request = new AuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setPaymentAction('Sale');
    }

    public function capture($options = null)
    {
        $request = new CaptureRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function refund($options = null)
    {
        $request = new RefundRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function send(RequestInterface $request)
    {
        $url = $this->getEndpoint().'?'.http_build_query($request->getData());
        $httpResponse = $this->httpClient->get($url)->send();

        return $this->createResponse($request, $httpResponse->getBody());
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
