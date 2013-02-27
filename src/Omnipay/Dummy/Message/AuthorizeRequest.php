<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $data = array();
        $data['amount'] = $this->getAmount();
        $data['card'] = $this->getCard();

        return $data;
    }

    public function send()
    {
        $data = $this->getData();
        $data['reference'] = uniqid();

        return $this->response = new Response($this, $data);
    }
}
