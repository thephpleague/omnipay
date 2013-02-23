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
    protected $action = 'A';
    protected $username;
    protected $password;
    protected $vendor;
    protected $partner;
    protected $testMode;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

        return $this;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($value)
    {
        $this->vendor = $value;

        return $this;
    }

    public function getPartner()
    {
        return $this->partner;
    }

    public function setPartner($value)
    {
        $this->partner = $value;

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

    protected function getBaseData()
    {
        $data = array();
        $data['TRXTYPE'] = $this->action;
        $data['USER'] = $this->username;
        $data['PWD'] = $this->password;
        $data['VENDOR'] = $this->vendor;
        $data['PARTNER'] = $this->partner;

        return $data;
    }

    public function getData()
    {
        $this->validate(array('amount', 'card'));
        $this->card->validate();

        $data = $this->getBaseData();
        $data['TENDER'] = 'C';
        $data['AMT'] = $this->getAmountDecimal();
        $data['COMMENT1'] = $this->getDescription();

        $data['ACCT'] = $this->card->getNumber();
        $data['EXPDATE'] = $this->card->getExpiryDate('my');
        $data['CVV2'] = $this->card->getCvv();
        $data['BILLTOFIRSTNAME'] = $this->card->getFirstName();
        $data['BILLTOLASTNAME'] = $this->card->getLastName();
        $data['BILLTOSTREET'] = $this->card->getAddress1();
        $data['BILLTOCITY'] = $this->card->getCity();
        $data['BILLTOSTATE'] = $this->card->getState();
        $data['BILLTOZIP'] = $this->card->getPostcode();
        $data['BILLTOCOUNTRY'] = $this->card->getCountry();

        return $data;
    }

    public function createResponse($gatewayReference)
    {
        return new Response($gatewayReference);
    }
}
