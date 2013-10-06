<?php

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
        return '1' === $this->getCode();
    }

    public function getCode()
    {
        return $this->data[0];
    }

    public function getReasonCode()
    {
        return $this->data[2];
    }

    public function getMessage()
    {
        return $this->data[3];
    }

    public function getAuthorizationCode()
    {
        return $this->data[4];
    }

    public function getAVSCode()
    {
        return $this->data[5];
    }

    public function getTransactionReference()
    {
        return $this->data[6];
    }
}
