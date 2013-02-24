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
    protected $apiKey;

    public function getName()
    {
        return 'Stripe';
    }

    public function defineSettings()
    {
        return array(
            'apiKey' => '',
        );
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($value)
    {
        $this->apiKey = $value;

        return $this;
    }

    public function purchase(array $options = null)
    {
        $request = new PurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function refund(array $options = null)
    {
        $request = new RefundRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
