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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        if (empty($data)) {
            throw new InvalidResponseException;
        }

        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        var_dump($this->data);

        return false;
    }

    public function getTransactionReference()
    {
        var_dump($this->data);

        return false;
    }

    public function getMessage()
    {
        var_dump($this->data);

        return false;
    }
}
