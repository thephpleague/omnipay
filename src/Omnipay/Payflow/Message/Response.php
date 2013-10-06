<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Payflow Response
 */
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
        return isset($this->data['RESULT']) && '0' === $this->data['RESULT'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['PNREF']) ? $this->data['PNREF'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['RESPMSG']) ? $this->data['RESPMSG'] : null;
    }
}
