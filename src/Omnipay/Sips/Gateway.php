<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Sips;

use Omnipay\Common\AbstractGateway;

/**
 * Sips Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Sips';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'sipsFolderPath' => ''
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function getSipsFolderPath()
    {
        return $this->getParameter('sipsFolderPath');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function setSipsFolderPath($value)
    {
        return $this->setParameter('sipsFolderPath', $value);
    }

    public function purchase(array $parameters = array())
    {
        $parameters['merchandId'] = $this->getMerchantId();
        $parameters['sipsFolderPath'] = $this->getSipsFolderPath();

        return $this->createRequest('\Omnipay\Sips\Message\AuthorizeRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sips\Message\CompletePurchaseRequest', $parameters);
    }

    public function cancelPurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sips\Message\ReturnRequest', $parameters);
    }
}
