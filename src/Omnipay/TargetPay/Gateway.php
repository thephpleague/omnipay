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

use Omnipay\Common\AbstractGateway;
use Omnipay\TargetPay\Message\CompletePurchaseRequest;
use Omnipay\TargetPay\Message\FetchIssuersRequest;

/**
 * TargetPay gateway.
 *
 * @link https://www.targetpay.com/docs/TargetPay_MisterCash_V1.0_nl.pdf
 * @link https://www.targetpay.com/docs/TargetPay_iDEAL_V1.0_nl.pdf
 * @link https://www.targetpay.com/info/directebanking-docu
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'TargetPay';
    }

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

    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    /**
     * Retrieve iDEAL issuers.
     *
     * @param array $parameters An array of options
     *
     * @return FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        $class = '\Omnipay\TargetPay\Message\\'.ucfirst($this->getPaymentMethod()).'PurchaseRequest';

        return $this->createRequest($class, $parameters);
    }

    /**
     * Complete a purchase.
     *
     * @param array $parameters An array of options
     *
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\TargetPay\Message\CompletePurchaseRequest', $parameters);
    }
}
