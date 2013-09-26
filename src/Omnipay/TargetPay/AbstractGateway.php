<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
