<?php

namespace Omnipay\Manual\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Manual Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return true;
    }
}
