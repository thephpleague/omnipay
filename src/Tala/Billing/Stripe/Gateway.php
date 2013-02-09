<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Stripe;

use Tala\AbstractGateway;
use Tala\Request;

/**
 * Stripe Gateway
 *
 * @link https://stripe.com/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.stripe.com/v1';
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
    }

    public function purchase($options)
    {
        $data = $this->buildPurchase($options);

        return $this->send('/charges', $data);
    }

    public function refund($options)
    {
        $request = new Request($options);
        $request->validate(array('gatewayReference', 'amount'));
        $data = array('amount' => $request->getAmount());

        return $this->send('/charges/'.$request->getGatewayReference().'/refund', $data);
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $source = $request->getCard();

        $data = array();
        $data['amount'] = $request->getAmount();
        $data['card'] = $source;
        $data['currency'] = strtolower($request->getCurrency());
        $data['description'] = $request->getDescription();

        return $data;
    }

    protected function send($url, $data)
    {
        $response = $this->httpClient->post($this->endpoint.$url, $data);

        return new Response($response);
    }
}
