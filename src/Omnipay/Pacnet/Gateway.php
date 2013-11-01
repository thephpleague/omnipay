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
            'username'                  => '',
            'sharedSecret'              => '',
            'paymentRoutingNumber'      => '',
            'testMode'                  => false
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

    public function getSharedSecret()
    {
        return $this->getParameter('sharedSecret');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    public function getPaymentRoutingNumber()
    {
        return $this->getParameter('paymentRoutingNumber');
    }

    public function setPaymentRoutingNumber($value)
    {
        return $this->setParameter('paymentRoutingNumber', $value);
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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pacnet\Message\AuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pacnet\Message\CaptureRequest', $parameters);
    }
}
