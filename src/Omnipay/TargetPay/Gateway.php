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
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\TargetPay\Message\CompletePurchaseRequest;
use Omnipay\TargetPay\Message\FetchIssuersRequest;

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
        $paymentMethod = $this->getPaymentMethod();

        switch ($paymentMethod) {
            case 'mrcash':
                $class = 'MrcashPurchaseRequest';
                break;
            case 'ideal':
                $class = 'IdealPurchaseRequest';
                break;
            case 'directebanking':
                $class = 'DirectebankingPurchaseRequest';
                break;
            default:
                throw new InvalidRequestException(sprintf('Unknown payment method "%s".', $paymentMethod));
        }

        return $this->createRequest('\Omnipay\TargetPay\Message\\'.$class, $parameters);
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
