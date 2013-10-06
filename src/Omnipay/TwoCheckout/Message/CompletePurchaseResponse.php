<?php

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * 2Checkout Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }

    public function getTransactionReference()
    {
        return isset($this->data['order_number']) ? $this->data['order_number'] : null;
    }
}
