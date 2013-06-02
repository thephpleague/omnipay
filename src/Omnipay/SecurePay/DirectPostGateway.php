<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay;

use Omnipay\Common\AbstractGateway;

/**
 * SecurePay Direct Post Gateway
 *
 * @link http://www.securepay.com.au/uploads/Integration%20Guides/Direct_Post_Integration_Guide.pdf
 */
class DirectPostGateway extends AbstractGateway
{
    public function getName()
    {
        return 'SecurePay Direct Post';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'transactionPassword' => '',
            'testMode' => false,
        );
    }

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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SecurePay\Message\DirectPostAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SecurePay\Message\DirectPostCompletePurchaseRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SecurePay\Message\DirectPostPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SecurePay\Message\DirectPostCompletePurchaseRequest', $parameters);
    }
}
