<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Currency;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Abstract Request
 */
abstract class AbstractRequest implements RequestInterface
{
    protected $httpRequest;
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
    protected $response;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array An associative array of parameters
     */
    public function initialize($options)
    {
        Helper::initialize($this, $options);

        return $this;
    }

    /**
     * Validate the request
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the request is clearly invalid.
     *
     * @param array an array of required parameters
     */
    public function validate(array $required)
    {
        foreach ($required as $key) {
            if (empty($this->$key)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function setHttpRequest(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        return $this;
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

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = (int) $value;

        return $this;
    }

    public function getAmountDecimal()
    {
        return number_format(
            $this->amount / $this->getCurrencyDecimalFactor(),
            $this->getCurrencyDecimalPlaces(),
            '.',
            ''
        );
    }

    public function getCurrency()
    {
        return $this->currency ? $this->currency->getCode() : null;
    }

    public function setCurrency($value)
    {
        $this->currency = Currency::find($value);

        return $this;
    }

    public function getCurrencyNumeric()
    {
        return $this->currency ? $this->currency->getNumeric() : null;
    }

    public function getCurrencyDecimalPlaces()
    {
        return $this->currency ? $this->currency->getDecimals() : 2;
    }

    private function getCurrencyDecimalFactor()
    {
        return pow(10, $this->getCurrencyDecimalPlaces());
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;

        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($value)
    {
        $this->transactionId = $value;

        return $this;
    }

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }

    public function setGatewayReference($value)
    {
        $this->gatewayReference = $value;

        return $this;
    }

    public function getClientIp()
    {
        return $this->clientIp;
    }

    public function setClientIp($value)
    {
        $this->clientIp = $value;

        return $this;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function setReturnUrl($value)
    {
        $this->returnUrl = $value;

        return $this;
    }

    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    public function setCancelUrl($value)
    {
        $this->cancelUrl = $value;

        return $this;
    }

    public function send()
    {
        throw new RuntimeException('FIXME');
    }

    public function getResponse()
    {
        if (null === $this->response) {
            throw new RuntimeException('You must call send() before accessing the Response!');
        }

        return $this->response;
    }
}
