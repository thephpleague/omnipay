<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Payments;

use Tala\Payments\Exception\InvalidRequest;

/**
 * Credit Card class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class Request
{
    protected $amount;
    protected $currency;
    protected $source;
    protected $description;
    protected $returnUrl;
    protected $cancelUrl;
    protected $gatewayReference;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }

    public function getAmountDollars()
    {
        return sprintf('%0.2f', $this->getAmount() / 100);
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($value)
    {
        $this->currency = $value;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($value)
    {
        $this->source = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
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

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }

    public function setGatewayReference($value)
    {
        $this->gatewayReference = $value;
    }

    /**
     * Validate that the specific parameters are not empty.
     */
    public function validateRequiredParams($params)
    {
        if ( ! is_array($params)) {
            $params = array($params);
        }

        foreach ($params as $key) {
            if (empty($this->$key)) {
                throw new InvalidRequest("The $key parameter is required!");
            }
        }
    }
}
