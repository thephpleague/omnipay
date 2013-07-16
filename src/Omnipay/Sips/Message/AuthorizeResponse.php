<?php

namespace Omnipay\Sips\Message;

use Omnipay\Sips\Message\AuthorizeRequest;

/**
 * Sips Authorize Response
 */
class AuthorizeResponse extends Response
{
    protected function getResultComponents()
    {
        return array(
            'code' => 1,
            'debug' => 2,
            'message' => 3
        );
    }

    public function setData()
    {
        $this->validate('amount', 'card');

        $this->getCard()->validate();

        return array('amount' => $this->getAmount());
    }
}
