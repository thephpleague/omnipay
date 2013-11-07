<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://test.adyen.com/hpp/pay.shtml';

    public function getMerchantAccount()
    {
        return $this->getParameter('merchantAccount');
    }

    public function setMerchantAccount($value)
    {
        return $this->setParameter('merchantAccount', $value);
    }

    public function getCurrencyCode()
    {
        return $this->getParameter('currencyCode');
    }

    public function setCurrencyCode($value)
    {
        return $this->setParameter('currencyCode', $value);
    }

    public function getSkinCode()
    {
        return $this->getParameter('skinCode');
    }

    public function setSkinCode($value)
    {
        return $this->setParameter('skinCode', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getShipBeforeDate()
    {
        return $this->getParameter('shipBeforeDate');
    }

    public function setShipBeforeDate($value)
    {
        return $this->setParameter('shipBeforeDate', $value);
    }

    public function getSessionValidity()
    {
        return $this->getParameter('sessionValidity');
    }

    public function setSessionValidity($value)
    {
        return $this->setParameter('sessionValidity', $value);
    }

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getShopperLocale()
    {
        return $this->getParameter('shopperLocale');
    }

    public function setShopperLocale($value)
    {
        return $this->setParameter('shopperLocale', $value);
    }

    public function getAllowedMethods()
    {
        return $this->getParameter('allowedMethods');
    }

    public function setAllowedMethods($value)
    {
        return $this->setParameter('allowedMethods', $value);
    }

    public function getData()
    {
        $this->validate('merchantAccount', 'secret', 'amount');
        $data = array();
        $data['amount'] = $this->getAmount();
        $data['currencyCode'] = $this->getCurrencyCode();
        $data['shipBeforeDate'] = $this->getShipBeforeDate();
        $data['merchantReference'] = $this->getMerchantReference();
        $data['skinCode'] = $this->getSkinCode();
        $data['merchantAccount'] = $this->getMerchantAccount();
        $data['sessionValidity'] = $this->getSessionValidity();
        $data['merchantSig'] = $this->generateSignature($data);

        return $data;
    }

    public function generateSignature($data)
    {
        return base64_encode(
            hash_hmac(
                'sha1',
                $data['amount'].
                $data['currencyCode'].
                $data['shipBeforeDate'].
                $data['merchantReference'].
                $data['skinCode'].
                $data['merchantAccount'].
                $data['sessionValidity'],
                $this->getSecret(),
                true
            )
        );
    }
    
    public function send()
    {
        $data = $this->getData();
        
        if ($this->getParameter('testMode') !== true) {
            $data['paymentAmount'] = $data['amount'];
            unset($data['amount']);
        }
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
