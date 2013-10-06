<?php

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
    public function getName()
    {
        return 'PaymentExpress PxPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
        );
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxPayAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxPayCompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxPayPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }
}
