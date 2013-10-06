<?php

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    public function getSubAccountId()
    {
        return $this->getParameter('subAccountId');
    }

    public function setSubAccountId($value)
    {
        return $this->setParameter('subAccountId', $value);
    }

    /**
     * Get the endpoint for the request.
     *
     * @return string
     */
    abstract public function getEndpoint();
}
