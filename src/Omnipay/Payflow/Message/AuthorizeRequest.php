<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Payflow\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Payflow Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://payflowpro.paypal.com';
    protected $testEndpoint = 'https://pilot-payflowpro.paypal.com';
    protected $action = 'A';

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['TRXTYPE'] = $this->action;
        $data['USER'] = $this->getUsername();
        $data['PWD'] = $this->getPassword();
        $data['VENDOR'] = $this->getVendor();
        $data['PARTNER'] = $this->getPartner();

        if ($this->getClientIp()) {
            $data['CUSTIP'] = $this->getClientIp();
        }

        return $data;
    }

    public function getData()
    {
        $data = $this->getBaseData();
        $data['TENDER'] = 'C';
        $data['AMT'] = $this->getAmount();
        $data['INVNUM'] = $this->getTransactionId();
        $data['DESC'] = $this->getDescription();
    
        if ($this->getCardReference()) {
            $this->validate('amount');

            $data['ORIGID'] = $this->getCardReference();

            // if card variables are set, override
            // the original transaction values
            if ($card = $this->getCard()) {

                if ($card->getNumber()) {
                    $data['ACCT'] = $card->getNumber();
                }
                if ($card->getExpiryMonth() && $card->getExpiryYear()) {
                    $data['EXPDATE'] = $card->getExpiryDate('my');
                }
                if ($card->getFirstName()) {
                    $data['BILLTOFIRSTNAME'] = $card->getFirstName();
                }
                if ($card->getLastName()) {
                    $data['BILLTOLASTNAME'] = $card->getLastName();
                }
                if ($card->getAddress1()) {
                    $data['BILLTOSTREET'] = $card->getAddress1();
                }
                if ($card->getCity()) {
                    $data['BILLTOCITY'] = $card->getCity();
                }
                if ($card->getState()) {
                    $data['BILLTOSTATE'] = $card->getState();
                }
                if ($card->getPostcode()) {
                    $data['BILLTOZIP'] = $card->getPostcode();
                }
                if ($card->getCountry()) {
                    $data['BILLTOCOUNTRY'] = $card->getCountry();
                }
            }
        } else {
            $this->validate('amount', 'card');
            $this->getCard()->validate();

            $data['ACCT'] = $this->getCard()->getNumber();
            $data['EXPDATE'] = $this->getCard()->getExpiryDate('my');
            $data['CVV2'] = $this->getCard()->getCvv();
            $data['BILLTOFIRSTNAME'] = $this->getCard()->getFirstName();
            $data['BILLTOLASTNAME'] = $this->getCard()->getLastName();
            $data['BILLTOSTREET'] = $this->getCard()->getAddress1();
            $data['BILLTOCITY'] = $this->getCard()->getCity();
            $data['BILLTOSTATE'] = $this->getCard()->getState();
            $data['BILLTOZIP'] = $this->getCard()->getPostcode();
            $data['BILLTOCOUNTRY'] = $this->getCard()->getCountry();
        }

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
