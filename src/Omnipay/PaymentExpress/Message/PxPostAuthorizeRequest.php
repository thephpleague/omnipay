<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPost Authorize Request
 */
class PxPostAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Auth';
    protected $username;
    protected $password;

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

    protected function getBaseData()
    {
        $data = new \SimpleXMLElement('<Txn />');
        $data->PostUsername = $this->username;
        $data->PostPassword = $this->password;
        $data->TxnType = $this->action;

        return $data;
    }

    public function getData()
    {
        $this->validate(array('amount', 'card'));
        $this->card->validate();

        $data = $this->getBaseData();
        $data->InputCurrency = $this->getCurrency();
        $data->Amount = $this->getAmountDecimal();
        $data->MerchantReference = $this->getDescription();

        $data->CardNumber = $this->card->getNumber();
        $data->CardHolderName = $this->card->getName();
        $data->DateExpiry = $this->card->getExpiryDate('my');
        $data->Cvc2 = $this->card->getCvv();

        return $data;
    }

    public function createResponse($data)
    {
        return new Response($data);
    }
}
