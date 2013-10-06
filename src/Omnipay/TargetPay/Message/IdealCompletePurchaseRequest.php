<?php

namespace Omnipay\TargetPay\Message;

class IdealCompletePurchaseRequest extends CompletePurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'https://www.targetpay.com/ideal/check';
    }
}
