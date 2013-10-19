<?php

namespace Omnipay\FirstData;

use Omnipay\Common\AbstractGateway;

class ConnectGateway extends AbstractGateway
{
    public function getName()
    {
        return 'First Data Connect';
    }

    public function getDefaultParameters()
    {
        return array(
            'storeId' => '',
            'sharedSecret' => '',
            'testMode' => false,
        );
    }

    public function setStoreId($value)
    {
        return $this->setParameter('storeId', $value);
    }

    public function getStoreId()
    {
        return $this->getParameter('storeId');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('sharedSecret');
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstData\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstData\Message\CompletePurchaseRequest', $parameters);
    }
}
