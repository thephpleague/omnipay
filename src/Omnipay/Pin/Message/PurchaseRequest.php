<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Pin\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Pin Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.pin.net.au/1';
    protected $testEndpoint = 'https://test-api.pin.net.au/1';
    protected $secretKey;
    protected $testMode;

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($value)
    {
        $this->secretKey = $value;

        return $this;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount'));

        $data = array();
        $data['amount'] = $this->getAmount();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['ip_address'] = $this->getClientIp();

        if ($this->card) {
            $this->card->validate();

            $data['card']['number'] = $this->card->getNumber();
            $data['card']['expiry_month'] = $this->card->getExpiryMonth();
            $data['card']['expiry_year'] = $this->card->getExpiryYear();
            $data['card']['cvc'] = $this->card->getCvv();
            $data['card']['name'] = $this->card->getName();
            $data['card']['address_line1'] = $this->card->getAddress1();
            $data['card']['address_line2'] = $this->card->getAddress2();
            $data['card']['address_city'] = $this->card->getCity();
            $data['card']['address_postcode'] = $this->card->getPostcode();
            $data['card']['address_state'] = $this->card->getState();
            $data['card']['address_country'] = $this->card->getCountry();
            $data['email'] = $this->card->getEmail();
        }

        return $data;
    }

    public function send()
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

        $httpResponse = $this->httpClient->post($this->getEndpoint().'/charges', null, $this->getData())
            ->setHeader('Authorization', 'Basic '.base64_encode($this->secretKey.':'))
            ->send();

        return $this->response = new Response($this, $httpResponse->json());
    }

    public function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
