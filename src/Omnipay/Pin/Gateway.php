<?php

namespace Omnipay\Pin;

use Omnipay\Common\AbstractGateway;
use Omnipay\Pin\Message\PurchaseRequest;

/**
 * Pin Gateway
 *
 * @link https://pin.net.au/docs/api
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Pin';
    }

    public function getDefaultParameters()
    {
        return array(
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pin\Message\PurchaseRequest', $parameters);
    }
}
