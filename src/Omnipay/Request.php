<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay;

use Omnipay\Helper;
use Omnipay\Exception\InvalidRequestException;

/**
 * Request
 */
class Request
{
    protected $card;
    protected $token;
    protected $amount;
    protected $currency;
    protected $description;
    protected $transactionId;
    protected $gatewayReference;
    protected $clientIp;
    protected $returnUrl;
    protected $cancelUrl;

    /**
     * Create a new Request
     *
     * @param array an array of initial paramters
     */
    public function __construct($parameters = array())
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array An associative array of parmaters
     */
    public function initialize($parameters)
    {
        Helper::initialize($this, $parameters);
    }

    /**
     * Validate the request
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the request is clearly invalid.
     *
     * @param array an array of required parameters
     */
    public function validate($required)
    {
        foreach ($required as $key) {
            if (empty($this->$key)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    public function getCard()
    {
        return $this->card;
    }

    public function setCard($value)
    {
        if (is_array($value)) {
            $value = new CreditCard($value);
        }

        $this->card = $value;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = (int) round($value);
    }

    public function getAmountDollars()
    {
        return number_format($this->amount / 100, $this->getCurrencyDecimals(), '.', '');
    }

    public function getCurrency()
    {
        return $this->currency ? $this->currency->getCode() : null;
    }

    public function setCurrency($value)
    {
        $this->currency = Currency::find($value);
    }

    public function getCurrencyNumeric()
    {
        return $this->currency ? $this->currency->getNumeric() : null;
    }

    public function getCurrencyDecimals()
    {
        return $this->currency ? $this->currency->getDecimals() : 2;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($value)
    {
        $this->transactionId = $value;
    }

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }

    public function setGatewayReference($value)
    {
        $this->gatewayReference = $value;
    }

    public function getClientIp()
    {
        return $this->clientIp;
    }

    public function setClientIp($value)
    {
        $this->clientIp = $value;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function setReturnUrl($value)
    {
        $this->returnUrl = $value;
    }

    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    public function setCancelUrl($value)
    {
        $this->cancelUrl = $value;
    }
}
