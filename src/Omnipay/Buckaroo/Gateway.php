<?php

namespace Omnipay\Buckaroo;

use Omnipay\Common\AbstractGateway;

/**
 * Buckaroo Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Buckaroo';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'secret' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Buckaroo\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Buckaroo\Message\CompletePurchaseRequest', $parameters);
    }
}
