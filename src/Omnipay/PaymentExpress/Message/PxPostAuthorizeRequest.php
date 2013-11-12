<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPost Authorize Request
 */
class PxPostAuthorizeRequest extends AbstractRequest
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxpost.aspx';
    protected $action = 'Auth';

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

    protected function getBaseData()
    {
        $data = new \SimpleXMLElement('<Txn />');
        $data->PostUsername = $this->getUsername();
        $data->PostPassword = $this->getPassword();
        $data->TxnType = $this->action;

        return $data;
    }

    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();
        $data->InputCurrency = $this->getCurrency();
        $data->Amount = $this->getAmount();
        $data->MerchantReference = $this->getDescription();

        if ($this->getCardReference()) {
            $data->DpsBillingId = $this->getCardReference();
        } elseif ($this->getCard()) {
            $this->getCard()->validate();
            $data->CardNumber = $this->getCard()->getNumber();
            $data->CardHolderName = $this->getCard()->getName();
            $data->DateExpiry = $this->getCard()->getExpiryDate('my');
            $data->Cvc2 = $this->getCard()->getCvv();
        } else {
            // either cardReference or card is required
            $this->validate('card');
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data->asXML())->send();

        return $this->response = new Response($this, $httpResponse->xml());
    }
}
