<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * @var string
     */
    protected $code;

    /**
     * {@inheritdoc}
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (false !== preg_match('/^([A-Z0-9]{6})(.*)$/', $this->data, $matches)) {
            $this->code = trim($matches[1]);
            $this->data = trim($matches[2]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        if (!$this->isSuccessful()) {
            return $this->code;
        }

        return null;
    }
}
