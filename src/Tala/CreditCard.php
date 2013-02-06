<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala;

use Tala\Exception\InvalidCreditCardException;
use Tala\AbstractParameterObject;

/**
 * Credit Card class
 */
class CreditCard extends AbstractParameterObject
{
    public function getName()
    {
        return trim("$this->firstName $this->lastName");
    }

    public function setName($value)
    {
        $names = explode(' ', $value, 2);
        $this->firstName = $names[0];
        $this->lastName = isset($names[1]) ? $names[1] : null;
    }

    public function setNumber($value)
    {
        $this->parameters['number'] = preg_replace('/\D/', '', $value);
    }

    public function setExpiryMonth($value)
    {
        $this->parameters['expiryMonth'] = (int) $value;
    }

    public function setExpiryYear($value)
    {
        $this->parameters['expiryYear'] = $this->normalizeYear($value);
    }

    public function setStartMonth($value)
    {
        $this->parameters['startMonth'] = (int) $value;
    }

    public function setStartYear($value)
    {
        $this->parameters['startYear'] = $this->normalizeYear($value);
    }

    /**
     * Normalize a year to four digits
     */
    protected function normalizeYear($value)
    {
        $value = (int) $value;
        if ($value < 100) {
            $value += 2000;
        }

        return $value;
    }

    /**
     * Get the card expiry date, using the specified date format
     */
    public function getExpiryDate($format = 'mY')
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->expiryMonth, 1, $this->expiryYear));
    }

    /**
     * Get the card start date, using the specified date format
     */
    public function getStartDate($format = 'mY')
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->startMonth, 1, $this->startYear));
    }

    public function getAddress1()
    {
        return $this->billingAddress1;
    }

    public function setAddress1($value)
    {
        $this->billingAddress1 = $value;
        $this->shippingAddress1 = $value;
    }

    public function getAddress2()
    {
        return $this->billingAddress2;
    }

    public function setAddress2($value)
    {
        $this->billingAddress2 = $value;
        $this->shippingAddress2 = $value;
    }

    public function getCity()
    {
        return $this->billingCity;
    }

    public function setCity($value)
    {
        $this->billingCity = $value;
        $this->shippingCity = $value;
    }

    public function getPostcode()
    {
        return $this->billingPostcode;
    }

    public function setPostcode($value)
    {
        $this->billingPostcode = $value;
        $this->shippingPostcode = $value;
    }

    public function getState()
    {
        return $this->billingState;
    }

    public function setState($value)
    {
        $this->billingState = $value;
        $this->shippingState = $value;
    }

    public function getCountry()
    {
        return $this->billingCountry;
    }

    public function setCountry($value)
    {
        $this->billingCountry = $value;
        $this->shippingCountry = $value;
    }

    /**
     * Validate the credit card number using the Luhn alogorithm. If the card number is invalid,
     * an InvalidCreditCard exception is thrown.
     */
    public function validateNumber()
    {
        if ( ! Helper::validateLuhn($this->number)) {
            throw new InvalidCreditCardException("The credit card number is invalid");
        }
    }
}
