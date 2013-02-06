<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Pin;

use Tala\AbstractGateway;
use Tala\CreditCard;
use Tala\Request;

/**
 * Pin Gateway
 *
 * @link https://pin.net.au/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.pin.net.au/1';
    protected $testEndpoint = 'https://test-api.pin.net.au/1';

    public function getDefaultSettings()
    {
        return array(
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildPurchase($request, $source);

        return $this->send('/charges', $data);
    }

    protected function buildPurchase(Request $request, $source)
    {
        $data = array();
        $data['amount'] = $request->amount;
        $data['currency'] = strtolower($request->currency);
        $data['description'] = $request->description;
        $data['ip_address'] = $this->httpRequest->getClientIp();

        if ($source instanceof CreditCard) {
            $source->validate();

            $data['card']['number'] = $source->getNumber();
            $data['card']['expiry_month'] = $source->getExpiryMonth();
            $data['card']['expiry_year'] = $source->getExpiryYear();
            $data['card']['cvc'] = $source->getCvv();
            $data['card']['name'] = $source->getName();
            $data['card']['address_line1'] = $source->getAddress1();
            $data['card']['address_line2'] = $source->getAddress2();
            $data['card']['address_city'] = $source->getCity();
            $data['card']['address_postcode'] = $source->getPostcode();
            $data['card']['address_state'] = $source->getState();
            $data['card']['address_country'] = $source->getCountry();
            $data['email'] = $source->getEmail();
        } else {
            $data['card_token'] = $source;
        }

        return $data;
    }

    protected function send($url, $data)
    {
        $headers = array('Authorization: Basic '.base64_encode($this->secretKey.':'));
        $response = $this->httpClient->post($this->getCurrentEndpoint().$url, $data, $headers);

        return new Response($response);
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
