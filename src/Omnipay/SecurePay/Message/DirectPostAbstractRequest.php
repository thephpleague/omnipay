<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * SecurePay Direct Post Abstract Request
 */
abstract class DirectPostAbstractRequest extends AbstractRequest
{
    public $testEndpoint = 'https://api.securepay.com.au/test/directpost/authorise';
    public $liveEndpoint = 'https://api.securepay.com.au/live/directpost/authorise';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getTransactionPassword()
    {
        return $this->getParameter('transactionPassword');
    }

    public function setTransactionPassword($value)
    {
        return $this->setParameter('transactionPassword', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
