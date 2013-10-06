<?php

namespace Omnipay\TargetPay;

use Omnipay\Common\AbstractGateway as BaseAbstractGateway;

/**
 * Abstract TargetPay gateway.
 */
abstract class AbstractGateway extends BaseAbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'subAccountId' => '',
        );
    }

    public function getSubAccountId()
    {
        return $this->getParameter('subAccountId');
    }

    public function setSubAccountId($value)
    {
        return $this->setParameter('subAccountId', $value);
    }
}
