<?php

namespace Omnipay\SagePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\SagePay\Message\CaptureRequest;
use Omnipay\SagePay\Message\DirectAuthorizeRequest;
use Omnipay\SagePay\Message\DirectPurchaseRequest;
use Omnipay\SagePay\Message\RefundRequest;

/**
 * Sage Pay Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Sage Pay Direct';
    }

    public function getDefaultParameters()
    {
        return array(
            'vendor' => '',
            'testMode' => false,
            'simulatorMode' => false,
        );
    }

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getSimulatorMode()
    {
        return $this->getParameter('simulatorMode');
    }

    public function setSimulatorMode($value)
    {
        return $this->setParameter('simulatorMode', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SagePay\Message\DirectAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SagePay\Message\DirectCompleteAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SagePay\Message\CaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SagePay\Message\DirectPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SagePay\Message\RefundRequest', $parameters);
    }
}
