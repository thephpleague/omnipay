<?php

namespace Omnipay\TargetPay\Message;

class MrcashCompletePurchaseRequest extends CompletePurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'https://www.targetpay.com/mrcash/check';
    }
}
