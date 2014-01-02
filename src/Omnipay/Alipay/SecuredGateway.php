<?php

namespace Omnipay\Alipay;

/**
 * Class SecuredGateway
 *
 * @package Omnipay\Alipay
 */
class SecuredGateway extends BaseAbstractGateway
{

    /**
     * LOGISTIC_TYPE
     */
    const LOGISTIC_TYPE_EXPRESS = 'EXPRESS';

    const LOGISTIC_TYPE_POST = 'POST';

    const LOGISTIC_TYPE_EMS = 'EMS';

    /**
     * LOGISTIC_PAYMENT
     */
    const LOGISTIC_PAYMENT_SELLER_PAY = 'SELLER_PAY';

    const LOGISTIC_PAYMENT_BUYER_PAY = 'BUYER_PAY';

    protected $service_name = 'create_partner_trade_by_buyer';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Alipay Secured';
    }

    function setLogisticsInfo($fee, $type, $payment)
    {
        $this->setLogisticsFee($fee);
        $this->setLogisticsType($type);
        $this->setLogisticsPayment($payment);
    }

    function getLogisticsFee()
    {
        return $this->getParameter('logistics_fee');
    }

    function setLogisticsFee($value)
    {
        $this->setParameter('logistics_fee', $value);
    }

    function getLogisticsType()
    {
        return $this->getParameter('logistics_type');
    }

    function setLogisticsType($value)
    {
        $this->setParameter('logistics_type', $value);
    }

    function getLogisticsPayment()
    {
        return $this->getParameter('logistics_payment');
    }

    function setLogisticsPayment($value)
    {
        $this->setParameter('logistics_payment', $value);
    }

    function setReceiveInfo($name, $address, $zip, $phone, $mobile)
    {
        $this->setReceiveName($name);
        $this->setReceiveAddress($address);
        $this->setReceiveZip($zip);
        $this->setReceivePhone($phone);
        $this->setReceiveMobile($mobile);
    }

    function getReceiveName()
    {
        return $this->getParameter('receive_name');
    }

    function setReceiveName($value)
    {
        $this->setParameter('receive_name', $value);
    }

    function getReceiveAddress()
    {
        return $this->getParameter('receive_address');
    }

    function setReceiveAddress($value)
    {
        $this->setParameter('receive_address', $value);
    }

    function getReceiveZip()
    {
        return $this->getParameter('receive_zip');
    }

    function setReceiveZip($value)
    {
        $this->setParameter('receive_zip', $value);
    }

    function getReceivePhone()
    {
        return $this->getParameter('receive_phone');
    }

    function setReceivePhone($value)
    {
        $this->setParameter('receive_phone', $value);
    }

    function getReceiveMobile()
    {
        return $this->getParameter('receive_mobile');
    }

    function setReceiveMobile($value)
    {
        $this->setParameter('receive_mobile', $value);
    }

    public function purchase(array $parameters = array())
    {
        $this->setService($this->service_name);
        return $this->createRequest('\Omnipay\Alipay\Message\SecuredPurchaseRequest', $parameters);
    }
}
