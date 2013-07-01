<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://secure.authorize.net/gateway/transact.dll';
    protected $developerEndpoint = 'https://test.authorize.net/gateway/transact.dll';

    public function getApiLoginId()
    {
        return $this->getParameter('apiLoginId');
    }

    public function setApiLoginId($value)
    {
        return $this->setParameter('apiLoginId', $value);
    }

    public function getTransactionKey()
    {
        return $this->getParameter('transactionKey');
    }

    public function setTransactionKey($value)
    {
        return $this->setParameter('transactionKey', $value);
    }

    public function getDeveloperMode()
    {
        return $this->getParameter('developerMode');
    }

    public function setDeveloperMode($value)
    {
        return $this->setParameter('developerMode', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['x_login'] = $this->getApiLoginId();
        $data['x_tran_key'] = $this->getTransactionKey();
        $data['x_type'] = $this->action;
        $data['x_version'] = '3.1';
        $data['x_delim_data'] = 'TRUE';
        $data['x_delim_char'] = ',';
        $data['x_encap_char'] = '|';
        $data['x_relay_response'] = 'FALSE';

        return $data;
    }

    protected function getBillingData()
    {
        $data = array();
        $data['x_amount'] = $this->getAmount();
        $data['x_invoice_num'] = $this->getTransactionId();
        $data['x_description'] = $this->getDescription();

        if ($card = $this->getCard()) {
            // customer billing details
            $data['x_first_name'] = $card->getBillingFirstName();
            $data['x_last_name'] = $card->getBillingLastName();
            $data['x_company'] = $card->getBillingCompany();
            $data['x_address'] = trim(
                $card->getBillingAddress1()." \n".
                $card->getBillingAddress2()
            );
            $data['x_city'] = $card->getBillingCity();
            $data['x_state'] = $card->getBillingState();
            $data['x_zip'] = $card->getBillingPostcode();
            $data['x_country'] = $card->getBillingCountry();
            $data['x_phone'] = $card->getBillingPhone();
            $data['x_email'] = $card->getEmail();

            // customer shipping details
            $data['x_ship_to_first_name'] = $card->getShippingFirstName();
            $data['x_ship_to_last_name'] = $card->getShippingLastName();
            $data['x_ship_to_company'] = $card->getShippingCompany();
            $data['x_ship_to_address'] = trim(
                $card->getShippingAddress1()." \n".
                $card->getShippingAddress2()
            );
            $data['x_ship_to_city'] = $card->getShippingCity();
            $data['x_ship_to_state'] = $card->getShippingState();
            $data['x_ship_to_zip'] = $card->getShippingPostcode();
            $data['x_ship_to_country'] = $card->getShippingCountry();
        }

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new AIMResponse($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->getDeveloperMode() ? $this->developerEndpoint : $this->liveEndpoint;
    }
}
