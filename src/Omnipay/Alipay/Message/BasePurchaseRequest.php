<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-1 下午9:06
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

abstract class BasePurchaseRequest extends BaseAbstractRequest
{

    protected $liveEndpoint = 'https://mapi.alipay.com/gateway.do';

    protected function validateData()
    {
        $this->validate(
            'service',
            'partner',
            'key',
            'seller_email',
            'notify_url',
            'return_url',
            'out_trade_no',
            'subject',
            'input_charset'
        );
    }

    function getService()
    {
        return $this->getParameter('service');
    }

    function setService($value)
    {
        $this->setParameter('service', $value);
    }

    function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }

    function setOutTradeNo($value)
    {
        $this->setParameter('out_trade_no', $value);
    }

    function getTransport()
    {
        return $this->getParameter('transport');
    }

    function setTransport($value)
    {
        $this->setParameter('transport', $value);
    }

    function getAntiPhishingKey()
    {
        return $this->getParameter('anti_phishing_key');
    }

    function setAntiPhishingKey($value)
    {
        $this->setParameter('anti_phishing_key', $value);
    }

    function getExterInvokeIp()
    {
        return $this->getParameter('exter_invoke_ip');
    }

    function setExterInvokeIp($value)
    {
        $this->setParameter('exter_invoke_ip', $value);
    }

    function getBody()
    {
        return $this->getParameter('body');
    }

    function setBody($value)
    {
        $this->setParameter('body', $value);
    }

    function getPartner()
    {
        return $this->getParameter('partner');
    }

    function setPartner($value)
    {
        $this->setParameter('partner', $value);
    }

    function getShowUrl()
    {
        return $this->getParameter('show_url');
    }

    function setShowUrl($value)
    {
        $this->setParameter('show_url', $value);
    }

    function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    function setInputCharset($value)
    {
        $this->setParameter('input_charset', $value);
    }

    function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    function setNotifyUrl($value)
    {
        $this->setParameter('notify_url', $value);
    }

    function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }

    function setReturnUrl($value)
    {
        $this->setParameter('return_url', $value);
    }

    function getSellerEmail()
    {
        return $this->getParameter('seller_email');
    }

    function setSellerEmail($value)
    {
        $this->setParameter('seller_email', $value);
    }

    function getSubject()
    {
        return $this->getParameter('subject');
    }

    function setSubject($value)
    {
        $this->setParameter('subject', $value);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    function getEndpoint()
    {
        return $this->liveEndpoint;
    }
}