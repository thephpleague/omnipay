<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Realex\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Realex Redirect Purchase Request
 */
class RedirectPurchaseRequest extends AbstractRequest
{
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    protected function getTimestamp()
    {
        return date('YmdHis');
    }

    protected function getHash()
    {
        $baseHash = sha1("{$this->getTimestamp()}.{$this->getUsername()}.{$this->getTransactionId()}.{$this->getAmount()}.{$this->getCurrency()}");

        return sha1($baseHash . '.' . $this->getSecret());
    }

    protected function getBaseData()
    {
        $data = array();
        $data['MERCHANT_ID'] = $this->getUsername();
        $data['SHA1HASH'] = $this->getHash();

        return $data;
    }

    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();
        $data['ORDER_ID'] = $this->getTransactionId();
        $data['CURRENCY'] = $this->getCurrency();
        $data['AMOUNT'] = $this->getAmount();
        $data['TIMESTAMP'] = $this->getTimestamp();
        $data['AUTO_SETTLE_FLAG'] = 1;

        return $data;
    }

    public function send()
    {
        return $this->response = new RedirectPurchaseResponse($this, $this->getData());
    }
}
