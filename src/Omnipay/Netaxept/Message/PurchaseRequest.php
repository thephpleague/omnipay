<?php

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Netaxept Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://epayment.bbs.no';
    protected $testEndpoint = 'https://epayment-test.bbs.no';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'currency', 'transactionId', 'returnUrl');

        $data = array();
        $data['merchantId'] = $this->getMerchantId();
        $data['token'] = $this->getPassword();
        $data['serviceType'] = 'B';
        $data['orderNumber'] = $this->getTransactionId();
        $data['currencyCode'] = $this->getCurrency();
        $data['amount'] = $this->getAmountInteger();
        $data['redirectUrl'] = $this->getReturnUrl();

        if ($this->getCard()) {
            $data['customerFirstName'] = $this->getCard()->getFirstName();
            $data['customerLastName'] = $this->getCard()->getLastName();
            $data['customerEmail'] = $this->getCard()->getEmail();
            $data['customerPhoneNumber'] = $this->getCard()->getPhone();
            $data['customerAddress1'] = $this->getCard()->getAddress1();
            $data['customerAddress2'] = $this->getCard()->getAddress2();
            $data['customerPostcode'] = $this->getCard()->getPostcode();
            $data['customerTown'] = $this->getCard()->getCity();
            $data['customerCountry'] = $this->getCard()->getCountry();
        }

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint().'/Netaxept/Register.aspx?';
        $httpResponse = $this->httpClient->get($url.http_build_query($data))->send();

        return $this->response = new Response($this, $httpResponse->xml());
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
