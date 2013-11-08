<?php

namespace Omnipay\Pin\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Pin Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.pin.net.au/1';
    protected $testEndpoint = 'https://test-api.pin.net.au/1';

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'card');

        $data = array();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['ip_address'] = $this->getClientIp();
        $data['email'] = $this->getCard()->getEmail();

        if ($this->getToken()) {
            $data['card_token'] = $this->getToken();
        } else {
            $this->getCard()->validate();

            $data['card']['number'] = $this->getCard()->getNumber();
            $data['card']['expiry_month'] = $this->getCard()->getExpiryMonth();
            $data['card']['expiry_year'] = $this->getCard()->getExpiryYear();
            $data['card']['cvc'] = $this->getCard()->getCvv();
            $data['card']['name'] = $this->getCard()->getName();
            $data['card']['address_line1'] = $this->getCard()->getAddress1();
            $data['card']['address_line2'] = $this->getCard()->getAddress2();
            $data['card']['address_city'] = $this->getCard()->getCity();
            $data['card']['address_postcode'] = $this->getCard()->getPostcode();
            $data['card']['address_state'] = $this->getCard()->getState();
            $data['card']['address_country'] = $this->getCard()->getCountry();
        }

        return $data;
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($this->getEndpoint().'/charges', null, $data)
            ->setHeader('Authorization', 'Basic '.base64_encode($this->getSecretKey().':'))
            ->send();

        return $this->response = new Response($this, $httpResponse->json());
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
