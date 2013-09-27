<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\FirstDataConnect;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'FirstDataConnect';
    }

    public function getDefaultParameters()
    {
        return array(
            'storeId' => '',
            'sharedSecret' => '',
            'testMode' => false,
        );
    }

    public function setStoreId($value)
    {
        return $this->setParameter('storeId', $value);
    }

    public function getStoreId()
    {
        return $this->getParameter('storeId');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('sharedSecret');
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstDataConnect\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstDataConnect\Message\CompletePurchaseRequest', $parameters);
    }
}
