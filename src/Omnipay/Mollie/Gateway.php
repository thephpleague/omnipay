<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;

/**
 * Mollie (iDeal) Gateway
 *
 * @link https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/en/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Mollie';
    }

    public function getDefaultParameters()
    {
        return array(
            'partnerId' => '',
            'testMode' => '',
        );
    }

    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }

    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }

    public function fetchIssuers(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\FetchIssuersRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\CompletePurchaseRequest', $parameters);
    }
}
