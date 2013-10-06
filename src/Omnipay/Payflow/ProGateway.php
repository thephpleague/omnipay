<?php

namespace Omnipay\Payflow;

use Omnipay\Common\AbstractGateway;
use Omnipay\Payflow\Message\AuthorizeRequest;
use Omnipay\Payflow\Message\CaptureRequest;
use Omnipay\Payflow\Message\PurchaseRequest;
use Omnipay\Payflow\Message\RefundRequest;

/**
 * Payflow Pro Class
 *
 * @link https://www.x.com/sites/default/files/payflowgateway_guide.pdf
 */
class ProGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Payflow';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'vendor' => '',
            'partner' => '',
            'testMode' => false,
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

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payflow\Message\AuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payflow\Message\CaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payflow\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Payflow\Message\RefundRequest', $parameters);
    }
}
