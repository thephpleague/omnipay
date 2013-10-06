<?php

namespace Omnipay\TargetPay\Message;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return '000000' === $this->code;
    }
}
