<?php

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
    public function getName()
    {
        return '2Checkout';
    }

    public function getDefaultParameters()
    {
        return array(
            'accountNumber' => '',
            'secretWord' => '',
            'testMode' => false,
        );
    }

    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    public function getSecretWord()
    {
        return $this->getParameter('secretWord');
    }

    public function setSecretWord($value)
    {
        return $this->setParameter('secretWord', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TwoCheckout\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TwoCheckout\Message\CompletePurchaseRequest', $parameters);
    }
}
