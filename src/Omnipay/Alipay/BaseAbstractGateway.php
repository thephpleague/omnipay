<?php

namespace Omnipay\Alipay;

use Omnipay\Common\AbstractGateway;

/**
 * Alipay Base Gateway Class
 */
abstract class BaseAbstractGateway extends AbstractGateway
{

    public function getDefaultParameters()
    {
        return array(
            'partner'      => '',
            'key'          => '',
            'signType'     => 'MD5',
            'inputCharset' => 'utf-8',
            'transport'    => 'http',
        );
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        return $this->setParameter('sign_type', $value);
    }

    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    public function setInputCharset($value)
    {
        return $this->setParameter('input_charset', $value);
    }

    public function getTransport()
    {
        return $this->getParameter('transport');
    }

    public function setTransport($value)
    {
        return $this->setParameter('transport', $value);
    }

    public function getAntiPhishingKey()
    {
        return $this->getParameter('anti_phishing_key');
    }

    public function setAntiPhishingKey($value)
    {
        return $this->setParameter('anti_phishing_key', $value);
    }

    public function getExterInvokeIp()
    {
        return $this->getParameter('exter_invoke_ip');
    }

    public function setExterInvokeIp($value)
    {
        return $this->setParameter('exter_invoke_ip', $value);
    }

    public function getBody()
    {
        return $this->getParameter('body');
    }

    public function setBody($value)
    {
        return $this->setParameter('body', $value);
    }

    public function getShowUrl()
    {
        return $this->getParameter('show_url');
    }

    public function setShowUrl($value)
    {
        return $this->setParameter('show_url', $value);
    }

    public function getSellerEmail()
    {
        return $this->getParameter('seller_email');
    }

    public function setSellerEmail($value)
    {
        $this->setParameter('seller_email', $value);
    }

    public function getService()
    {
        return $this->getParameter('service');
    }

    public function setService($value)
    {
        $this->setParameter('service', $value);
    }

    public function getDefaultBank()
    {
        return $this->getParameter('default_bank');
    }

    public function setDefaultBank($value)
    {
        $this->setParameter('default_bank', $value);
    }

    public function getPayMethod()
    {
        return $this->getParameter('pay_method');
    }

    public function setPayMethod($value)
    {
        $this->setParameter('pay_method', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Alipay\Message\ExpressPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Alipay\Message\ExpressCompletePurchaseRequest', $parameters);
    }
}
