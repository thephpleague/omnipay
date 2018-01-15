<?php

namespace Omnipay\Tpay\_class_tpay\PaymentOptions;

use Omnipay\Tpay\_class_tpay\Utilities\ObjectsHelper;
use Omnipay\Tpay\_class_tpay\Utilities\TException;

class CardOptions extends ObjectsHelper
{
    public $cardsApiURL = 'https://secure.tpay.com/api/cards/';
    protected $currency = 985;
    protected $orderID = '';
    protected $oneTimer = true;
    protected $lang = 'pl';
    protected $enablePowUrl = false;
    protected $powUrl = '';
    protected $powUrlBlad = '';
    protected $cardData = null;
    protected $method = 'register_sale';
    protected $clientAuthCode = '';
    protected $amount;

    public function __construct()
    {
        $this->isNotEmptyString($this->cardApiKey, 'Card API key');
        $this->isNotEmptyString($this->cardApiPass, 'Card API password');
        $this->validateCardHashAlg($this->cardHashAlg);
        $this->validateCardCode($this->cardVerificationCode);

    }

    public function setClientToken($token)
    {
        if (!is_string($token) || strlen($token) !== 40) {
            throw new TException('invalid token');
        } else {
            $this->clientAuthCode = $token;
        }
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = $this->validateCardCurrency($currency);
        return $this;
    }

    /**
     * @param $orderID
     * @return $this
     */
    public function setOrderID($orderID)
    {
        $this->orderID = $orderID;
        return $this;
    }

    public function setOneTimer($oneTimer)
    {
        $this->oneTimer = $oneTimer;
        return $this;
    }

    public function setLanguage($lang)
    {
        $this->lang = $this->validateCardLanguage($lang);
        return $this;
    }

    public function setEnablePowUrl($enablePowUrl)
    {
        $this->enablePowUrl = $enablePowUrl;
        return $this;
    }

    public function setReturnUrls($successUrl, $errorUrl)
    {
        $this->powUrl = $successUrl;
        $this->powUrlBlad = $errorUrl;
        return $this;
    }

    public function setCardData($data)
    {
        $this->cardData = $data;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->validateNumeric($amount);
        $this->amount = $amount;
        return $this;
    }
}
