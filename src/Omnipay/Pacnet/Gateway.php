<?php

namespace Omnipay\Pacnet;

use Omnipay\Common\AbstractGateway;

/**
 * Pacnet Gateway
 *
 * @link http://docs.pacnetservices.com/raven/api-guide/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Pacnet';
    }

    public function getDefaultParameters()
    {
        return array(
            'UserName'      => '',
            'SharedSecret'  => '',
            'PRN'           => '',
            'testMode'      => false
        );
    }

    public function getUserName()
    {
        return $this->getParameter('UserName');
    }

    public function setUserName($value)
    {
        return $this->setParameter('UserName', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('SharedSecret');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('SharedSecret', $value);
    }

    public function getPRN()
    {
        return $this->getParameter('PRN');
    }

    public function setPRN($value)
    {
        return $this->setParameter('PRN', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pacnet\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pacnet\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pacnet\Message\VoidRequest', $parameters);
    }
}
