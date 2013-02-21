<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common;

use Closure;
use Omnipay\Common\Exception\InvalidCreditCardException;

/**
 * Credit Card class
 */
class CreditCard
{
	const C_VISA = 'visa';
	const C_MASTER = 'mastercard';
	const C_DISCOVER = 'discover';
	const C_AMEX = 'americanexpress';
	const C_DINERSCLUB = 'diners_club';
	const C_JCB = 'jcb';
	const C_SWITCH = 'switch';
	const C_SOLO = 'solo';
	const C_DANKORT = 'dankort';
	const C_MAESTRO = 'maestro';
	const C_FORBRUGS = 'forbrugsforeningen';
	const C_LASER = 'laser';

    protected $firstName;
    protected $lastName;
    protected $number;
    protected $expiryMonth;
    protected $expiryYear;
    protected $startMonth;
    protected $startYear;
    protected $cvv;
    protected $issueNumber;
    protected $billingAddress1;
    protected $billingAddress2;
    protected $billingCity;
    protected $billingPostcode;
    protected $billingState;
    protected $billingCountry;
    protected $billingPhone;
    protected $shippingAddress1;
    protected $shippingAddress2;
    protected $shippingCity;
    protected $shippingPostcode;
    protected $shippingState;
    protected $shippingCountry;
    protected $shippingPhone;
    protected $company;
    protected $email;
	protected $brand;
	
	/**
	 * The brand to fall back to if no brand is detected
	 */
	protected $defaultBrand = CreditCard::C_VISA;
	
	/**
	 * Supported card brands and the regular expressions used to detect/validate them
	 */
	protected $supportedBrands = array(
		CreditCard::C_VISA => '/^4\d{12}(\d{3})?$/',
		CreditCard::C_MASTER => '/^(5[1-5]\d{4}|677189)\d{10}$/',
		CreditCard::C_DISCOVER => '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/',
		CreditCard::C_AMEX => '/^3[47]\d{13}$/',
		CreditCard::C_DINERSCLUB => '/^3(0[0-5]|[68]\d)\d{11}$/',
		CreditCard::C_JCB => '/^35(28|29|[3-8]\d)\d{12}$/',
		CreditCard::C_SWITCH => '/^6759\d{12}(\d{2,3})?$/',
		CreditCard::C_SOLO => '/^6767\d{12}(\d{2,3})?$/',
		CreditCard::C_DANKORT => '/^5019\d{12}$/',
		CreditCard::C_MAESTRO => '/^(5[06-8]|6\d)\d{10,17}$/',
		CreditCard::C_FORBRUGS => '/^600722\d{10}$/',
		CreditCard::C_LASER => '/^(6304|6706|6709|6771(?!89))\d{8}(\d{4}|\d{6,7})?$/'
	}
	
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
	
	public function getSupportedBrands()
	{
		return $this->supportedBrands;
	}
	
	public function setSupportedBrands(array $brands)
	{
		$this->supportedBrands = $brands;
	}
	
	/**
	 * Iterate through known card patterns to determine the brand of card
	 *
	 * @return string The brand of card determined, or a fallback in the event of no determination
	 */
	public function getBrand()
	{
		if ($this->brand)
		{
			return $this->brand;
		}
		
		foreach ($this->supportedBrands as $brand => $val)
		{
			$result = $val instanceOf Closure ? $val($this->number) : (bool) preg_match($val, $this->number);
		
			if ($result === true)
			{
				break;
			}
		}
		
		return $result ? $brand : $this->defaultBrand;
	}
	
	/**
	 * Determine if a card number belongs to a particular brand
	 *
	 * @param string $brand The brand to match against
	 * @param string $number Optionally specify a different card number to that stored in $this->number
	 * @return bool True if the card number belongs to the brand specified
	 */
	public function isBrand($brand, $number = null)
	{
		$number or $number = $this->number;
	
		if (array_key_exists($brand, $this->supportedBrands))
		{
			$result = $this->supportedBrands[$brand] instanceOf Closure
				? $this->supportedBrands[$brand]($number)
				: (bool) preg_match($this->supportedBrands[$brand], $number);
		}
		
		return isset($result) ? (bool) $result : false;
	}
	
	/**
	 * Used to add additional card brands and validation Closures to the CreditCard object
	 *
	 * @param string $brand Name of credit card brand, e.g. visa
	 * @param Closure|string $validationMethod A Closure or regex pattern which compares the card number against the given brand
	 */
	public function addBrand($brand, Closure $validationMethod)
	{
		$this->supportedBrands[$brand] = $validationMethod;
	}
	
	public function getDefaultBrand()
	{
		return $this->defaultBrand;
	}

	public function setDefaultBrand($value)
	{
		$this->defaultBrand = $value;
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

    public function getBillingPhone()
    {
        return $this->billingPhone;
    }

    public function setBillingPhone($value)
    {
        $this->billingPhone = $value;
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

    public function getShippingPhone()
    {
        return $this->shippingPhone;
    }

    public function setShippingPhone($value)
    {
        $this->shippingPhone = $value;
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

    public function getPhone()
    {
        return $this->billingPhone;
    }

    public function setPhone($value)
    {
        $this->billingPhone = $value;
        $this->shippingPhone = $value;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($value)
    {
        $this->company = $value;
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
