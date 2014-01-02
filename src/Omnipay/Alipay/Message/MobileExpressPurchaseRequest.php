<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-1 下午9:06
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

class MobileExpressPurchaseRequest extends BaseAbstractRequest
{
    protected $service = 'mobile.securitypay.pay';

    protected function validateData()
    {
        $this->validate(
            'partner',
            'out_trade_no',
            'subject',
            'total_fee',
            'notify_url',
            'show_url',
            'return_url',
            'private_key'
        );
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();
        $data                   = array(
            'partner'        => $this->getPartner(),
            'seller_id'      => $this->getPartner(),
            'out_trade_no'   => $this->getOutTradeNo(),
            'subject'        => $this->getSubject(),
            'body'           => $this->getBody(),
            'total_fee'      => $this->getTotalFee(),
            'notify_url'     => $this->getNotifyUrl(),
            'service'        => $this->service,
            '_input_charset' => 'utf-8',
            'payment_type'   => '1',
            'it_b_pay'       => $this->getItBPay(),
            'show_url'       => $this->getShowUrl(),
            'return_url'     => $this->getReturnUrl(),
        );
        $data                   = array_filter($data);
        $data['sign']           = $this->getParamsSignature($data);
        $data['sign_type']      = $this->getSignType();
        $orderInfoStr           = http_build_query($data);
        $orderInfoStr           = str_replace('&', '"&', $orderInfoStr);
        $orderInfoStr           = str_replace('=', '="', $orderInfoStr) . '"';
        $rsa_sign               = $this->rsaSign($orderInfoStr, $this->getPrivateKey());
        $resp['order_info_str'] = sprintf('%s&sign="%s"&sign_type="%s"', $orderInfoStr, urlencode($rsa_sign), 'RSA');
        return $resp;
    }

    function rsaSign($data, $private_key)
    {
        $res = openssl_pkey_get_private($private_key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    function setPrivateKey($value)
    {
        $this->setParameter('private_key', $value);
    }

    function getItBPay()
    {
        return $this->getParameter('it_b_pay');
    }

    function setItBPay($value)
    {
        $this->setParameter('it_b_pay', $value);
    }

    function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    function setTotalFee($value)
    {
        $this->setParameter('total_fee', $value);
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

    function getKey()
    {
        return $this->getParameter('key');
    }

    function setKey($value)
    {
        $this->setParameter('key', $value);
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

    function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    function setSignType($value)
    {
        $this->setParameter('sign_type', $value);
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
        return $this->response = new MobileExpressPurchaseResponse($this, $data);
    }
}