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

        $data = array();
        $data['amount'] = $request->getAmount();
        $data['currency'] = strtolower($request->getCurrency());
        $data['description'] = $request->getDescription();

        if ($card = $request->getCard()) {
            $card->validate();

            $data['card'] = array();
            $data['card']['number'] = $card->getNumber();
            $data['card']['exp_month'] = $card->getExpiryMonth();
            $data['card']['exp_year'] = $card->getExpiryYear();
            $data['card']['cvc'] = $card->getCvv();
            $data['card']['name'] = $card->getName();
            $data['card']['address_line1'] = $card->getAddress1();
            $data['card']['address_line2'] = $card->getAddress2();
            $data['card']['address_city'] = $card->getCity();
            $data['card']['address_zip'] = $card->getPostcode();
            $data['card']['address_state'] = $card->getState();
            $data['card']['address_country'] = $card->getCountry();
        } elseif ($token = $request->getToken()) {
            $data['card'] = $token;
        }

        return $data;
    }

    protected function send($url, $data)
    {
        $headers = array('Authorization: Basic '.base64_encode($this->apiKey.':'));
        $response = $this->httpClient->post($this->endpoint.$url, $data, $headers);

        return new Response($response);
    }
}
