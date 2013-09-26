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

class DirectebankingPurchaseRequest extends PurchaseRequest
{
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getServiceType()
    {
        return $this->getParameter('serviceType');
    }

    public function setServiceType($value)
    {
        return $this->setParameter('serviceType', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $this->validate('amount', 'description', 'country', 'serviceType', 'clientIp', 'returnUrl');

        return array(
            'rtlo' => $this->getSubAccountId(),
            'description' => $this->getDescription(),
            'amount' => $this->getAmountInteger(),
            'country' => $this->getCountry(),
            'lang' => $this->getLanguage(),
            'type' => $this->getServiceType(),
            'userip' => $this->getClientIp(),
            'returnurl' => $this->getReturnUrl(),
            'reporturl' => $this->getNotifyUrl(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return 'https://www.targetpay.com/directebanking/start';
    }
}
