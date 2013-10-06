<?php

namespace Omnipay\Manual\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Manual Request
 */
class Request extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount');

        return $this->getParameters();
    }

    public function send()
    {
        return $this->response = new Response($this, $this->getData());
    }
}
