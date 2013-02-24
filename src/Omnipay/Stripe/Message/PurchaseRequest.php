<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Stripe Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $apiKey;

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($value)
    {
        $this->apiKey = $value;

        return $this;
    }

    public function getData()
    {
        $this->validate(array('amount'));

        $data = array();
        $data['amount'] = $this->getAmount();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();

        if ($this->card) {
            $this->card->validate();

            $data['card'] = array();
            $data['card']['number'] = $this->card->getNumber();
            $data['card']['exp_month'] = $this->card->getExpiryMonth();
            $data['card']['exp_year'] = $this->card->getExpiryYear();
            $data['card']['cvc'] = $this->card->getCvv();
            $data['card']['name'] = $this->card->getName();
            $data['card']['address_line1'] = $this->card->getAddress1();
            $data['card']['address_line2'] = $this->card->getAddress2();
            $data['card']['address_city'] = $this->card->getCity();
            $data['card']['address_zip'] = $this->card->getPostcode();
            $data['card']['address_state'] = $this->card->getState();
            $data['card']['address_country'] = $this->card->getCountry();
        } elseif ($token = $this->getToken()) {
            $data['card'] = $token;
        }

        return $data;
    }

    public function getUrl()
    {
        return '/charges';
    }

    public function createResponse($data)
    {
        return new Response($data);
    }
}
