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

    function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    function setSignType($value)
    {
        return $this->setParameter('sign_type', $value);
    }

    function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    function setInputCharset($value)
    {
        return $this->setParameter('input_charset', $value);
    }

    function getTransport()
    {
        return $this->getParameter('transport');
    }

    function setTransport($value)
    {
        return $this->setParameter('transport', $value);
    }

    function getAntiPhishingKey()
    {
        return $this->getParameter('anti_phishing_key');
    }

    function setAntiPhishingKey($value)
    {
        return $this->setParameter('anti_phishing_key', $value);
    }

    function getExterInvokeIp()
    {
        return $this->getParameter('exter_invoke_ip');
    }

    function setExterInvokeIp($value)
    {
        return $this->setParameter('exter_invoke_ip', $value);
    }

    function getBody()
    {
        return $this->getParameter('body');
    }

    function setBody($value)
    {
        return $this->setParameter('body', $value);
    }

    function getShowUrl()
    {
        return $this->getParameter('show_url');
    }

    function setShowUrl($value)
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

    function getDefaultBank()
    {
        return $this->getParameter('default_bank');
    }

    function setDefaultBank($value)
    {
        $this->setParameter('default_bank', $value);
    }

    function getPayMethod()
    {
        return $this->getParameter('pay_method');
    }

    function setPayMethod($value)
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
