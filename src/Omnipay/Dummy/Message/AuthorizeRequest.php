<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Dummy Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');

        $this->getCard()->validate();

        return array('amount' => $this->getAmount());
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if ($datas) {
            $datas = $doMerge ? array_merge($this->getData(), $datas) : $datas;
        } else {
            $datas = $this->getData();
        }

        $datas['reference'] = uniqid();
        $datas['success'] = 0 === substr($this->getCard()->getNumber(), -1, 1) % 2;
        $datas['message'] = $datas['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $datas);
    }
}
