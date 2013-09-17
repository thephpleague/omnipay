<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PagSeguro\Message;

/**
 * PagSeguro Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
        $data = array();

        var_dump($this->getTransactionId());

        $data['email'] = $this->getEmail();
        $data['token'] = $this->getToken();
        $data['currency'] = $this->getCurrency();
        $data['itemId1'] = $this->getTransactionId();
        $data['itemAmount1'] = $this->getAmountInteger();
        $data['itemDescription1'] = $this->getDescription();

        return $data;
    }
}
