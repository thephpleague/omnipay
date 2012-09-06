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

use Tala\Payments\Exception\InvalidCreditCardException;
use Tala\Payments\AbstractParameterObject;

/**
 * Credit Card class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
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
        /*
         * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
         * This code has been released into the public domain, however please
         * give credit to the original author where possible.
         */

        // Set the string length and parity
        $number_length = strlen($this->number);
        $parity = $number_length % 2;

        // Loop through each digit and do the maths
        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $this->number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit *= 2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            // Total up the digits
            $total += $digit;
        }

        // If the total mod 10 does not equal 0, the number is invalid
        if ($total % 10 != 0) {
            throw new InvalidCreditCardException("The credit card number is invalid");
        }
    }
}
