<?php

namespace Omnipay\TargetPay;

use Omnipay\TargetPay\Message\CompletePurchaseRequest;
use Omnipay\TargetPay\Message\FetchIssuersRequest;

/**
 * TargetPay iDEAL gateway.
 *
 * @link https://www.targetpay.com/docs/TargetPay_iDEAL_V1.0_nl.pdf
 */
class IdealGateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'TargetPay iDEAL';
    }

    /**
     * Retrieve iDEAL issuers.
     *
     * @param array $parameters An array of options
     *
     * @return FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\IdealPurchaseRequest', $parameters);
    }

    /**
     * Complete a purchase.
     *
     * @param array $parameters An array of options
     *
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\IdealCompletePurchaseRequest', $parameters);
    }
}
