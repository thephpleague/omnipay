<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Pin;

use Guzzle\Common\Event;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Request;

/**
 * Pin Gateway
 *
 * @link https://pin.net.au/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.pin.net.au/1';
    protected $testEndpoint = 'https://test-api.pin.net.au/1';
    protected $secretKey;
    protected $testMode;

    public function getName()
    {
        return 'Pin';
    }

    public function defineSettings()
    {
        return array(
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($value)
    {
        $this->secretKey = $value;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function purchase($options)
    {
        $data = $this->buildPurchase($options);

        return $this->send('/charges', $data);
    }

    protected function buildPurchase($options)
    {
        $request = new Request($options);
        $request->validate(array('amount'));

        $data = array();
        $data['amount'] = $request->getAmount();
        $data['currency'] = strtolower($request->getCurrency());
        $data['description'] = $request->getDescription();
        $data['ip_address'] = $request->getClientIp();

        if ($card = $request->getCard()) {
            $card->validate();

            $data['card']['number'] = $card->getNumber();
            $data['card']['expiry_month'] = $card->getExpiryMonth();
            $data['card']['expiry_year'] = $card->getExpiryYear();
            $data['card']['cvc'] = $card->getCvv();
            $data['card']['name'] = $card->getName();
            $data['card']['address_line1'] = $card->getAddress1();
            $data['card']['address_line2'] = $card->getAddress2();
            $data['card']['address_city'] = $card->getCity();
            $data['card']['address_postcode'] = $card->getPostcode();
            $data['card']['address_state'] = $card->getState();
            $data['card']['address_country'] = $card->getCountry();
            $data['email'] = $card->getEmail();
        }

        return $data;
    }

    protected function send($url, $data)
    {
        // don't throw exceptions for 422 errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->getStatusCode() == 422) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($this->getCurrentEndpoint().$url, null, $data)
            ->setHeader('Authorization', 'Basic '.base64_encode($this->secretKey.':'))
            ->send();

        return new Response($httpResponse->json());
    }

    protected function getCurrentEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->endpoint;
    }
}
