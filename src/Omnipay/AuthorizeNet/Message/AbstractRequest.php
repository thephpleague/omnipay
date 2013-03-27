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
        $data['x_amount'] = $this->getAmountDecimal();
        $data['x_invoice_num'] = $this->getTransactionId();
        $data['x_description'] = $this->getDescription();

        if ($this->getCard()) {
            $data['x_first_name'] = $this->getCard()->getFirstName();
            $data['x_last_name'] = $this->getCard()->getLastName();
            $data['x_company'] = $this->getCard()->getCompany();
            $data['x_address'] = trim($this->getCard()->getAddress1()." \n".$this->getCard()->getAddress2());
            $data['x_city'] = $this->getCard()->getCity();
            $data['x_state'] = $this->getCard()->getState();
            $data['x_zip'] = $this->getCard()->getPostcode();
            $data['x_country'] = $this->getCard()->getCountry();
            $data['x_phone'] = $this->getCard()->getPhone();
            $data['x_email'] = $this->getCard()->getEmail();
        }

        return $data;
    }

    protected function getShippingData()
    {
        if (!$this->getCard()) {
            return array();
        }

        $data = array();
        $data['x_ship_to_address'] = trim(
            $this->getCard()->getShippingAddress1()
            . " \n"
            . $this->getCard()->getShippingAddress2()
        );
        $data['x_ship_to_city'] = $this->getCard()->getShippingCity();
        $data['x_ship_to_company'] = $this->getCard()->getShippingCompany();
        $data['x_ship_to_country'] = $this->getCard()->getShippingCountry();
        $data['x_ship_to_first_name'] = $this->getCard()->getShippingFirstName();
        $data['x_ship_to_last_name'] = $this->getCard()->getShippingLastName();
        $data['x_ship_to_state'] = $this->getCard()->getShippingState();
        $data['x_ship_to_zip'] = $this->getCard()->getShippingPostcode();

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
