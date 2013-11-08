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

    public function send(array $datas = array(), $doMerge = true)
    {
        if ($datas) {
            $datas = $doMerge ? array_merge($this->getData(), $datas) : $datas;
        } else {
            $datas = $this->getData();
        }

        return $this->response = new Response($this, $datas);
    }
}
