<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\Common\AbstractGateway;
use Omnipay\Stripe\Message\PurchaseRequest;
use Omnipay\Stripe\Message\RefundRequest;

/**
 * Stripe Gateway
 *
 * @link https://stripe.com/docs/api
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Stripe';
    }

    public function getDefaultParameters()
    {
        return array(
            'apiKey' => '',
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stripe\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stripe\Message\RefundRequest', $parameters);
    }

    public function store(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stripe\Message\StoreRequest', $parameters);
    }

    public function update(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stripe\Message\UpdateRequest', $parameters);
    }

    public function unstore(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Stripe\Message\UnstoreRequest', $parameters);
    }
}
