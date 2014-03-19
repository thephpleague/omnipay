<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-1 下午9:06
 *
 */
namespace Omnipay\Alipay\Message;

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
        $orderInfoStr           = urldecode($orderInfoStr); # utf-8 char fix
        $rsa_sign               = $this->rsaSign($orderInfoStr, $this->getPrivateKey());
        $resp['order_info_str'] = sprintf('%s&sign="%s"&sign_type="%s"', $orderInfoStr, urlencode($rsa_sign), 'RSA');
        return $resp;
    }

    public function rsaSign($data, $private_key)
    {
        $res = openssl_pkey_get_private($private_key);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value)
    {
        $this->setParameter('private_key', $value);
    }

    public function getItBPay()
    {
        return $this->getParameter('it_b_pay');
    }

    public function setItBPay($value)
    {
        $this->setParameter('it_b_pay', $value);
    }

    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    public function setTotalFee($value)
    {
        $this->setParameter('total_fee', $value);
    }

    public function getService()
    {
        return $this->getParameter('service');
    }

    public function setService($value)
    {
        $this->setParameter('service', $value);
    }

    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }

    public function setOutTradeNo($value)
    {
        $this->setParameter('out_trade_no', $value);
    }

    public function getTransport()
    {
        return $this->getParameter('transport');
    }

    public function setTransport($value)
    {
        $this->setParameter('transport', $value);
    }

    public function getAntiPhishingKey()
    {
        return $this->getParameter('anti_phishing_key');
    }

    public function setAntiPhishingKey($value)
    {
        $this->setParameter('anti_phishing_key', $value);
    }

    public function getExterInvokeIp()
    {
        return $this->getParameter('exter_invoke_ip');
    }

    public function setExterInvokeIp($value)
    {
        $this->setParameter('exter_invoke_ip', $value);
    }

    public function getBody()
    {
        return $this->getParameter('body');
    }

    public function setBody($value)
    {
        $this->setParameter('body', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        $this->setParameter('partner', $value);
    }

    public function getShowUrl()
    {
        return $this->getParameter('show_url');
    }

    public function setShowUrl($value)
    {
        $this->setParameter('show_url', $value);
    }

    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    public function setInputCharset($value)
    {
        $this->setParameter('input_charset', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        $this->setParameter('key', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl($value)
    {
        $this->setParameter('notify_url', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }

    public function setReturnUrl($value)
    {
        $this->setParameter('return_url', $value);
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        $this->setParameter('sign_type', $value);
    }

    public function getSubject()
    {
        return $this->getParameter('subject');
    }

    public function setSubject($value)
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
