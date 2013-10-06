<?php

namespace Omnipay\Eway;

use Omnipay\Common\AbstractGateway;

/**
 * eWAY Rapid 3.0 Gateway
 */
class RapidGateway extends AbstractGateway
{
    public function getName()
    {
        return 'eWAY Rapid 3.0';
    }

    public function getDefaultParameters()
    {
        return array(
            'apiKey' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\RapidPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\RapidCompletePurchaseRequest', $parameters);
    }
}
