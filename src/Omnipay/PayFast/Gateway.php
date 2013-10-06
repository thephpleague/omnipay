<?php

namespace Omnipay\PayFast;

use Omnipay\Common\AbstractGateway;

/**
 * PayFast Gateway
 *
 * Quote: The PayFast engine is basically a "black box" which processes a purchaser's payment.
 *
 * @link https://www.payfast.co.za/s/std/integration-guide
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayFast';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'merchantKey' => '',
            'pdtKey' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getPdtKey()
    {
        return $this->getParameter('pdtKey');
    }

    public function setPdtKey($value)
    {
        return $this->setParameter('pdtKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayFast\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayFast\Message\CompletePurchaseRequest', $parameters);
    }
}
