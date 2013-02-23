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
use Omnipay\Common\Message\RequestInterface;
use Omnipay\PaymentExpress\Message\PxPayAuthorizeRequest;
use Omnipay\PaymentExpress\Message\PxPayCompleteAuthorizeRequest;
use Omnipay\PaymentExpress\Message\PxPayPurchaseRequest;

/**
 * DPS PaymentExpress PxPay Gateway
 */
class PxPayGateway extends AbstractGateway
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxpay/pxaccess.aspx';
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

    public function authorize($options = null)
    {
        $request = new PxPayAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completeAuthorize($options = null)
    {
        $request = new PxPayCompleteAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function purchase($options = null)
    {
        $request = new PxPayPurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completePurchase($options = null)
    {
        return $this->completeAuthorize();
    }

    public function send(RequestInterface $request)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $request->getData()->asXML())->send();

        return $this->createResponse($request, $httpResponse->xml());
    }
}
