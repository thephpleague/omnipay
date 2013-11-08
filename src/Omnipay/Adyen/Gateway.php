<?php

namespace Omnipay\Adyen;

use Omnipay\Common\AbstractGateway;
use Omnipay\Adyen\Message\CompletePurchaseResponse;

/**
* Adyen Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Adyen';
    }

    public function getDefaultParameters()
    {
        return array(
            'testMode' => true,
            'secret' => 'test'
        );
    }

    public function getSessionValidity()
    {
        return $this->getParameter('sessionValidity');
    }

    public function setSessionValidity($value)
    {
        return $this->setParameter('sessionValidity', $value);
    }

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getMerchantAccount()
    {
        return $this->getParameter('merchantAccount');
    }

    public function setMerchantAccount($value)
    {
        return $this->setParameter('merchantAccount', $value);
    }

    public function getSkinCode()
    {
        return $this->getParameter('skinCode');
    }

    public function setSkinCode($value)
    {
        return $this->setParameter('skinCode', $value);
    }

    public function getShipBeforeDate()
    {
        return $this->getParameter('shipBeforeDate');
    }

    public function setShipBeforeDate($value)
    {
        return $this->setParameter('shipBeforeDate', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getShopperLocale()
    {
        return $this->getParameter('shopperLocale');
    }

    public function setShopperLocale($value)
    {
        return $this->setParameter('shopperLocale', $value);
    }

    public function getAllowedMethods()
    {
        return $this->getParameter('allowedMethods');
    }

    public function setAllowedMethods($value)
    {
        return $this->setParameter('allowedMethods', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Adyen\Message\PurchaseRequest', $parameters);
    }
    
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Adyen\Message\CompletePurchaseRequest', $parameters);
    }
}
