<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Authorize.Net AIM Response
 */
class AIMResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = explode('|,|', substr($data, 1, -1));

        if (count($this->data) < 10) {
            throw new InvalidResponseException();
        }
    }

    public function isSuccessful()
    {
        return '1' === $this->data[0];
    }

    public function getTransactionReference()
    {
        return $this->data[6];
    }

    public function getMessage()
    {
        return $this->data[3];
    }
}
