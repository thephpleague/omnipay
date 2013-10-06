<?php

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (isset($this->data->error)) {
            return (string) $this->data->error->description;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        if (isset($this->data->error)) {
            return (string) $this->data->error->code;
        }

        return null;
    }
}
