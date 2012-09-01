<?php

namespace Tala\Payments;

/**
 * Credit Card class
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class CreditCard
{
    protected $firstName;
    protected $lastName;
    protected $number;
    protected $expiryMonth;
    protected $expiryYear;
    protected $verificationCode;
    protected $billingAddress1;
    protected $billingAddress2;
    protected $billingCity;
    protected $billingPostcode;
    protected $billingState;
    protected $billingCountry;
    protected $shippingAddress1;
    protected $shippingAddress2;
    protected $shippingCity;
    protected $shippingPostcode;
    protected $shippingState;
    protected $shippingCountry;
    protected $errors;

    public function __construct($params = array())
    {
        foreach (array(
            'firstName',
            'lastName',
            'number',
            'expiryMonth',
            'expiryYear',
            ) as $key) {
            if (isset($params[$key])) {
                $this->$key = $params[$key];
            }
        }
    }

    public function isValid()
    {
        $this->errors = array();

        $this->validateNumber();

        return empty($this->errors);
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($value)
    {
        $this->firstName = $value;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($value)
    {
        $this->lastName = $value;
    }

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

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($value)
    {
        $this->number = preg_replace('/\D/', '', $value);
    }

    public function getExpiryMonth()
    {
        return $this->expiryMonth;
    }

    public function setExpiryMonth($value)
    {
        $this->expiryMonth = (int)$value;
    }

    public function getExpiryYear()
    {
        return $this->expiryYear;
    }

    public function setExpiryYear($value)
    {
        $value = (int)$value;
        if ($value < 100) {
            $value += 2000;
        }
        $this->expiryYear = $value;
    }

    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    public function setVerificationCode($value)
    {
        $this->verificationCode = $value;
    }

    public function getBillingAddress1()
    {
        return $this->billingAddress1;
    }

    public function setBillingAddress1($value)
    {
        $this->billingAddress1 = $value;
    }

    public function getBillingAddress2()
    {
        return $this->billingAddress2;
    }

    public function setBillingAddress2($value)
    {
        $this->billingAddress2 = $value;
    }

    public function getBillingCity()
    {
        return $this->billingCity;
    }

    public function setBillingCity($value)
    {
        $this->billingCity = $value;
    }

    public function getBillingPostcode()
    {
        return $this->billingPostcode;
    }

    public function setBillingPostcode($value)
    {
        $this->billingPostcode = $value;
    }

    public function getBillingState()
    {
        return $this->billingState;
    }

    public function setBillingState($value)
    {
        $this->billingState = $value;
    }

    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    public function setBillingCountry($value)
    {
        $this->billingCountry = $value;
    }

    public function getShippingAddress1()
    {
        return $this->shippingAddress1;
    }

    public function setShippingAddress1($value)
    {
        $this->shippingAddress1 = $value;
    }

    public function getShippingAddress2()
    {
        return $this->shippingAddress2;
    }

    public function setShippingAddress2($value)
    {
        $this->shippingAddress2 = $value;
    }

    public function getShippingCity()
    {
        return $this->shippingCity;
    }

    public function setShippingCity($value)
    {
        $this->shippingCity = $value;
    }

    public function getShippingPostcode()
    {
        return $this->shippingPostcode;
    }

    public function setShippingPostcode($value)
    {
        $this->shippingPostcode = $value;
    }

    public function getShippingState()
    {
        return $this->shippingState;
    }

    public function setShippingState($value)
    {
        $this->shippingState = $value;
    }

    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry($value)
    {
        $this->shippingCountry = $value;
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

	protected function validateNumber()
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
		for ($i = 0; $i < $number_length; $i++)
		{
			$digit = $this->number[$i];
			// Multiply alternate digits by two
			if ($i % 2 == $parity)
			{
				$digit *= 2;
				// If the sum is two digits, add them together (in effect)
				if ($digit > 9)
				{
					$digit -= 9;
				}
			}
			// Total up the digits
			$total += $digit;
		}

		// If the total mod 10 equals 0, the number is valid
		return ($total % 10 == 0) ? TRUE : FALSE;
    }
}
