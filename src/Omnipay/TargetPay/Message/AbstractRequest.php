<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TargetPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://www.targetpay.com/%s/%s';

    public function getSubAccountId()
    {
        return $this->getParameter('subAccountId');
    }

    public function setSubAccountId($value)
    {
        return $this->setParameter('subAccountId', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    /**
     * Get the endpoint for the provided operation.
     *
     * @param string $operation The operation to perform (start or check)
     *
     * @return string
     */
    protected function getEndpoint($operation)
    {
        return sprintf($this->endpoint, $this->getPaymentMethod(), $operation);
    }
}
