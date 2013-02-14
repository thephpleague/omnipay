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

use Omnipay\Exception\InvalidCreditCardException;

/**
 * Credit Card class
 */
class CreditCard
{
    protected $firstName;
    protected $lastName;
    protected $number;
    protected $expiryMonth;
    protected $expiryYear;
    protected $startMonth;
    protected $startYear;
    protected $cvv;
    protected $issueNumber;
    protected $type;
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
    protected $company;
    protected $phone;
    protected $email;

    /**
     * Create a new CreditCard object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->initialize($parameters);
    }

    /**
     * Set all parameters. It is safe to pass untrusted user input directly to this method.
     *
     * @param array $parameters An array of parameters to set on this object
     */
    public function initialize($parameters)
    {
        Helper::initialize($this, $parameters);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * Validate this credit card. If the card is invalid, InvalidCreditCardException is thrown.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the credit card is clearly invalid.
     *
     * Generally if you want to validate the credit card yourself with custom error
     * messages, you should use your framework's validation library, not this method.
     */
    public function validate()
    {
        foreach (array('number', 'firstName', 'lastName', 'expiryMonth', 'expiryYear', 'cvv') as $key) {
            if (empty($this->$key)) {
                throw new InvalidCreditCardException("The $key parameter is required");
            }
        }

        if ($this->getExpiryDate('Ym') < gmdate('Ym')) {
            throw new InvalidCreditCardException('Card has expired');
        }

        if (!Helper::validateLuhn($this->number)) {
            throw new InvalidCreditCardException('Card number is invalid');
        }
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
        $this->expiryMonth = (int) $value;
    }

    public function getExpiryYear()
    {
        return $this->expiryYear;
    }

    public function setExpiryYear($value)
    {
        $this->expiryYear = $value ? Helper::normalizeYear($value) : $value;
    }

    /**
     * Get the card expiry date, using the specified date format string
     *
     * @param string
     */
    public function getExpiryDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->expiryMonth, 1, $this->expiryYear));
    }

    public function getStartMonth()
    {
        return $this->startMonth;
    }

    public function setStartMonth($value)
    {
        $this->startMonth = (int) $value;
    }

    public function getStartYear()
    {
        return $this->startYear;
    }

    public function setStartYear($value)
    {
        $this->startYear = Helper::normalizeYear($value);
    }

    /**
     * Get the card start date, using the specified date format string
     *
     * @param string
     */
    public function getStartDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->startMonth, 1, $this->startYear));
    }

    public function getCvv()
    {
        return $this->cvv;
    }

    public function setCvv($value)
    {
        $this->cvv = $value;
    }

    public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    public function setIssueNumber($value)
    {
        $this->issueNumber = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
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

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($value)
    {
        $this->company = $value;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($value)
    {
        $this->phone = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }
}
