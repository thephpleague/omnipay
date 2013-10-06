<?php

namespace Omnipay\TargetPay\Message;

class DirectebankingCompletePurchaseRequest extends CompletePurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'https://www.targetpay.com/directebanking/check';
    }
}
