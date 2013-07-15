<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay;

use Omnipay\Common\AbstractGateway;

/**
 * Icepay gateway.
 *
 * @link http://www.icepay.com/downloads/pdf/documentation/icepay_implementation_guide.pdf
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Icepay';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'secretCode' => '',
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

    public function getSecretCode()
    {
        return $this->getParameter('secretCode');
    }

    public function setSecretCode($value)
    {
        return $this->setParameter('secretCode', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Icepay\Message\PurchaseRequest', $parameters);
    }
}
