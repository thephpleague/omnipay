<?php

namespace Omnipay\Common\Message;

use Mockery as m;
use Symfony\Component\HttpFoundation\Request;

class ExceptionCausingRequest extends AbstractRequest
{
    public function send()
    {
        // Mimic sending Response
        $this->response = m::mock('\Omnipay\Common\Message\ResponseInterface');
    }

    public function getData()
    {
    }
}
