<?php

namespace Omnipay\Pin\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Pin Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return !isset($this->data['error']);
    }

    public function getTransactionReference()
    {
        if (isset($this->data['response']['token'])) {
            return $this->data['response']['token'];
        }
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data['response']['status_message'];
        } else {
            return $this->data['error_description'];
        }
    }
}
