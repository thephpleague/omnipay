<?php

namespace Omnipay\TargetPay;

use Omnipay\TargetPay\Message\CompletePurchaseRequest;

/**
 * TargetPay Directebanking gateway.
 *
 * @link https://www.targetpay.com/info/directebanking-docu
 */
class DirectebankingGateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'TargetPay Directebanking';
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\DirectebankingPurchaseRequest', $parameters);
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
        return $this->createRequest('\Omnipay\TargetPay\Message\DirectebankingCompletePurchaseRequest', $parameters);
    }
}
