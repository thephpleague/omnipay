<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * PaymentExpress Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return 1 === (int) $this->data->Success;
    }

    public function getTransactionReference()
    {
        return empty($this->data->DpsTxnRef) ? null : (string) $this->data->DpsTxnRef;
    }

    public function getCardReference()
    {
        return empty($this->data->Transaction->DpsBillingId)
            ? null
            : (string) $this->data->Transaction->DpsBillingId;
    }

    public function getMessage()
    {
        if (isset($this->data->HelpText)) {
            return (string) $this->data->HelpText;
        } else {
            return (string) $this->data->ResponseText;
        }
    }
}
