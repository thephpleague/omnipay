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
            'secret_key' => '',
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
            $data['card']['number'] = $source->number;
            $data['card']['expiry_month'] = $source->expiryMonth;
            $data['card']['expiry_year'] = $source->expiryYear;
            $data['card']['cvc'] = $source->cvv;
            $data['card']['name'] = $source->name;
            $data['card']['address_line1'] = $source->address1;
            $data['card']['address_line2'] = $source->address2;
            $data['card']['address_city'] = $source->city;
            $data['card']['address_postcode'] = $source->postcode;
            $data['card']['address_state'] = $source->state;
            $data['card']['address_country'] = $source->country;
        } else {
            $data['card_token'] = $source;
        }

        return $data;
    }

    protected function send($url, $data)
    {
        $response = $this->httpClient->post($this->endpoint.$url, $data);

        return new Response($response);
    }
}
