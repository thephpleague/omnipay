<?php

namespace Omnipay\Stripe\Message;

/**
 * Stripe Delete Credit Card Request
 */
class DeleteCardRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('cardReference');

        return null;
    }

    public function getHttpMethod()
    {
        return 'DELETE';
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/customers/'.$this->getCardReference();
    }
}
