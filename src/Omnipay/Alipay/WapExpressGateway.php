<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午12:52
 *
 */
namespace Omnipay\Alipay;

use Omnipay\Alipay\Message\WapExpressAuthorizeResponse;
use Omnipay\Common\AbstractGateway;

/**
 * Class WapExpressGateway
 *
 * @package Omnipay\Alipay\Message
 */
class WapExpressGateway extends AbstractGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Alipay Wap Express';
    }

    public function getDefaultParameters()
    {
        return array(
            'partner'      => '',
            'key'          => '',
            'signType'     => 'MD5',
            'inputCharset' => 'utf-8',
        );
    }

    public function getSellerEmail()
    {
        return $this->getParameter('seller_email');
    }

    public function setSellerEmail($value)
    {
        return $this->setParameter('seller_email', $value);
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

    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
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

    public function tokenRequest(array $parameters = array())
    {
        $defaults                 = array();
        $defaults['out_trade_no'] = '1';
        $defaults['subject']      = '1';
        $defaults['total_fee']    = '1';
        $parameters               = array_merge($defaults, $parameters);
        return $this->createRequest('\Omnipay\Alipay\Message\WapExpressAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        /**
         * @var WapExpressAuthorizeResponse $response
         */
        $response = $this->tokenRequest($parameters)->send();
        if ($response->isSuccessful()) {
            $parameters['token'] = $response->getToken();
            return $this->createRequest('\Omnipay\Alipay\Message\WapExpressPurchaseRequest', $parameters);
        } else {
            return $this->createRequest('\Omnipay\Alipay\Message\WapExpressPurchaseRequest', $parameters);
        }
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Alipay\Message\WapExpressCompletePurchaseRequest', $parameters);
    }
}
