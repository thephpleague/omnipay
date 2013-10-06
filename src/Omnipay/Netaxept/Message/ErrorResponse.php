<?php

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Netaxept Response
 */
class ErrorResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return false;
    }

    public function getTransactionReference()
    {
        return $this->data['transactionId'];
    }

    public function getMessage()
    {
        return $this->data['responseCode'];
    }
}
