<?php

namespace Omnipay\PagSeguro;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PagSeguro';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'email'    => '',
            'token'    => '', //E659CD138C654E528F11F9E727C6BBC6
            'testMode' => false
        );
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagSeguro\Message\AuthorizeRequest', $parameters);
    }
    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagSeguro\Message\PurchaseRequest', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagSeguro\Message\CaptureRequest', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagSeguro\Message\RefundRequest', $parameters);
    }
}
