<?php

namespace Omnipay\Common;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Credit Card class
 */
class CreditCard
{
    const BRAND_VISA = 'visa';
    const BRAND_MASTERCARD = 'mastercard';
    const BRAND_DISCOVER = 'discover';
    const BRAND_AMEX = 'amex';
    const BRAND_DINERS_CLUB = 'diners_club';
    const BRAND_JCB = 'jcb';
    const BRAND_SWITCH = 'switch';
    const BRAND_SOLO = 'solo';
    const BRAND_DANKORT = 'dankort';
    const BRAND_MAESTRO = 'maestro';
    const BRAND_FORBRUGSFORENINGEN = 'forbrugsforeningen';
    const BRAND_LASER = 'laser';

    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * Create a new CreditCard object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * All known/supported card brands, and a regular expression to match them.
     *
     * The order of the card brands is important, as some of the regular expressions overlap.
     *
     * Note: The fact that this class knows about a particular card brand does not imply
     * that your gateway supports it.
     *
     * @return array
     * @link https://github.com/Shopify/active_merchant/blob/master/lib/active_merchant/billing/credit_card_methods.rb
     */
    public function getSupportedBrands()
    {
        return array(
            static::BRAND_VISA => '/^4\d{12}(\d{3})?$/',
            static::BRAND_MASTERCARD => '/^(5[1-5]\d{4}|677189)\d{10}$/',
            static::BRAND_DISCOVER => '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/',
            static::BRAND_AMEX => '/^3[47]\d{13}$/',
            static::BRAND_DINERS_CLUB => '/^3(0[0-5]|[68]\d)\d{11}$/',
            static::BRAND_JCB => '/^35(28|29|[3-8]\d)\d{12}$/',
            static::BRAND_SWITCH => '/^6759\d{12}(\d{2,3})?$/',
            static::BRAND_SOLO => '/^6767\d{12}(\d{2,3})?$/',
            static::BRAND_DANKORT => '/^5019\d{12}$/',
            static::BRAND_MAESTRO => '/^(5[06-8]|6\d)\d{10,17}$/',
            static::BRAND_FORBRUGSFORENINGEN => '/^600722\d{10}$/',
            static::BRAND_LASER => '/^(6304|6706|6709|6771(?!89))\d{8}(\d{4}|\d{6,7})?$/',
        );
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters->all();
    }

    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    protected function setYearParameter($key, $value)
    {
        // normalize year to four digits
        if (null === $value || '' === $value) {
            $value = null;
        } else {
            $value = (int) gmdate('Y', gmmktime(0, 0, 0, 1, 1, (int) $value));
        }

        return $this->setParameter($key, $value);
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
        foreach (array('number', 'expiryMonth', 'expiryYear') as $key) {
            if (!$this->getParameter($key)) {
                throw new InvalidCreditCardException("The $key parameter is required");
            }
        }

        if ($this->getExpiryDate('Ym') < gmdate('Ym')) {
            throw new InvalidCreditCardException('Card has expired');
        }

        if (!Helper::validateLuhn($this->getNumber())) {
            throw new InvalidCreditCardException('Card number is invalid');
        }
    }

    public function getTitle()
    {
        return $this->getBillingTitle();
    }

    public function setTitle($value)
    {
        $this->setBillingTitle($value);
        $this->setShippingTitle($value);

        return $this;
    }

    public function getFirstName()
    {
        return $this->getBillingFirstName();
    }

    public function setFirstName($value)
    {
        $this->setBillingFirstName($value);
        $this->setShippingFirstName($value);

        return $this;
    }

    public function getLastName()
    {
        return $this->getBillingLastName();
    }

    public function setLastName($value)
    {
        $this->setBillingLastName($value);
        $this->setShippingLastName($value);

        return $this;
    }

    public function getName()
    {
        return $this->getBillingName();
    }

    public function setName($value)
    {
        $this->setBillingName($value);
        $this->setShippingName($value);

        return $this;
    }

    public function getNumber()
    {
        return $this->getParameter('number');
    }

    public function setNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('number', preg_replace('/\D/', '', $value));
    }

    public function getNumberLastFour()
    {
        return substr($this->getNumber(), -4, 4) ?: null;
    }

    /**
     * Returns a masked credit card number with only the last 4 chars visible
     *
     * @param string $mask Character to use in place of numbers
     * @return string
     */
    public function getNumberMasked($mask = 'X')
    {
        $maskLength = strlen($this->getNumber()) - 4;

        return str_repeat($mask, $maskLength) . $this->getNumberLastFour();
    }

    /**
     * Credit Card Brand
     *
     * Iterates through known/supported card brands to determine the brand of this card
     *
     * @return string
     */
    public function getBrand()
    {
        foreach ($this->getSupportedBrands() as $brand => $val) {
            if (preg_match($val, $this->getNumber())) {
                return $brand;
            }
        }
    }

    public function getExpiryMonth()
    {
        return $this->getParameter('expiryMonth');
    }

    public function setExpiryMonth($value)
    {
        return $this->setParameter('expiryMonth', (int) $value);
    }

    public function getExpiryYear()
    {
        return $this->getParameter('expiryYear');
    }

    public function setExpiryYear($value)
    {
        return $this->setYearParameter('expiryYear', $value);
    }

    /**
     * Get the card expiry date, using the specified date format string.
     *
     * @param string $format
     *
     * @return string
     */
    public function getExpiryDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->getExpiryMonth(), 1, $this->getExpiryYear()));
    }

    public function getStartMonth()
    {
        return $this->getParameter('startMonth');
    }

    public function setStartMonth($value)
    {
        return $this->setParameter('startMonth', (int) $value);
    }

    public function getStartYear()
    {
        return $this->getParameter('startYear');
    }

    public function setStartYear($value)
    {
        return $this->setYearParameter('startYear', $value);
    }

    /**
     * Get the card start date, using the specified date format string
     *
     * @param string $format
     *
     * @return string
     */
    public function getStartDate($format)
    {
        return gmdate($format, gmmktime(0, 0, 0, $this->getStartMonth(), 1, $this->getStartYear()));
    }

    public function getCvv()
    {
        return $this->getParameter('cvv');
    }

    public function setCvv($value)
    {
        return $this->setParameter('cvv', $value);
    }

    public function getIssueNumber()
    {
        return $this->getParameter('issueNumber');
    }

    public function setIssueNumber($value)
    {
        return $this->setParameter('issueNumber', $value);
    }

    public function getBillingTitle()
    {
        return $this->getParameter('billingTitle');
    }

    public function setBillingTitle($value)
    {
        return $this->setParameter('billingTitle', $value);
    }

    public function getBillingName()
    {
        return trim($this->getBillingFirstName() . ' ' . $this->getBillingLastName());
    }

    public function setBillingName($value)
    {
        $names = explode(' ', $value, 2);
        $this->setBillingFirstName($names[0]);
        $this->setBillingLastName(isset($names[1]) ? $names[1] : null);

        return $this;
    }

    public function getBillingFirstName()
    {
        return $this->getParameter('billingFirstName');
    }

    public function setBillingFirstName($value)
    {
        return $this->setParameter('billingFirstName', $value);
    }

    public function getBillingLastName()
    {
        return $this->getParameter('billingLastName');
    }

    public function setBillingLastName($value)
    {
        return $this->setParameter('billingLastName', $value);
    }

    public function getBillingCompany()
    {
        return $this->getParameter('billingCompany');
    }

    public function setBillingCompany($value)
    {
        return $this->setParameter('billingCompany', $value);
    }

    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }

    public function getBillingAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }

    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }

    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }

    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }

    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }

    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }

    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }

    public function getBillingFax()
    {
        return $this->getParameter('billingFax');
    }

    public function setBillingFax($value)
    {
        return $this->setParameter('billingFax', $value);
    }

    public function getShippingTitle()
    {
        return $this->getParameter('shippingTitle');
    }

    public function setShippingTitle($value)
    {
        return $this->setParameter('shippingTitle', $value);
    }

    public function getShippingName()
    {
        return trim($this->getShippingFirstName() . ' ' . $this->getShippingLastName());
    }

    public function setShippingName($value)
    {
        $names = explode(' ', $value, 2);
        $this->setShippingFirstName($names[0]);
        $this->setShippingLastName(isset($names[1]) ? $names[1] : null);

        return $this;
    }

    public function getShippingFirstName()
    {
        return $this->getParameter('shippingFirstName');
    }

    public function setShippingFirstName($value)
    {
        return $this->setParameter('shippingFirstName', $value);
    }

    public function getShippingLastName()
    {
        return $this->getParameter('shippingLastName');
    }

    public function setShippingLastName($value)
    {
        return $this->setParameter('shippingLastName', $value);
    }

    public function getShippingCompany()
    {
        return $this->getParameter('shippingCompany');
    }

    public function setShippingCompany($value)
    {
        return $this->setParameter('shippingCompany', $value);
    }

    public function getShippingAddress1()
    {
        return $this->getParameter('shippingAddress1');
    }

    public function setShippingAddress1($value)
    {
        return $this->setParameter('shippingAddress1', $value);
    }

    public function getShippingAddress2()
    {
        return $this->getParameter('shippingAddress2');
    }

    public function setShippingAddress2($value)
    {
        return $this->setParameter('shippingAddress2', $value);
    }

    public function getShippingCity()
    {
        return $this->getParameter('shippingCity');
    }

    public function setShippingCity($value)
    {
        return $this->setParameter('shippingCity', $value);
    }

    public function getShippingPostcode()
    {
        return $this->getParameter('shippingPostcode');
    }

    public function setShippingPostcode($value)
    {
        return $this->setParameter('shippingPostcode', $value);
    }

    public function getShippingState()
    {
        return $this->getParameter('shippingState');
    }

    public function setShippingState($value)
    {
        return $this->setParameter('shippingState', $value);
    }

    public function getShippingCountry()
    {
        return $this->getParameter('shippingCountry');
    }

    public function setShippingCountry($value)
    {
        return $this->setParameter('shippingCountry', $value);
    }

    public function getShippingPhone()
    {
        return $this->getParameter('shippingPhone');
    }

    public function setShippingPhone($value)
    {
        return $this->setParameter('shippingPhone', $value);
    }

    public function getShippingFax()
    {
        return $this->getParameter('shippingFax');
    }

    public function setShippingFax($value)
    {
        return $this->setParameter('shippingFax', $value);
    }

    public function getAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    public function setAddress1($value)
    {
        $this->setParameter('billingAddress1', $value);
        $this->setParameter('shippingAddress1', $value);

        return $this;
    }

    public function getAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    public function setAddress2($value)
    {
        $this->setParameter('billingAddress2', $value);
        $this->setParameter('shippingAddress2', $value);

        return $this;
    }

    public function getCity()
    {
        return $this->getParameter('billingCity');
    }

    public function setCity($value)
    {
        $this->setParameter('billingCity', $value);
        $this->setParameter('shippingCity', $value);

        return $this;
    }

    public function getPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    public function setPostcode($value)
    {
        $this->setParameter('billingPostcode', $value);
        $this->setParameter('shippingPostcode', $value);

        return $this;
    }

    public function getState()
    {
        return $this->getParameter('billingState');
    }

    public function setState($value)
    {
        $this->setParameter('billingState', $value);
        $this->setParameter('shippingState', $value);

        return $this;
    }

    public function getCountry()
    {
        return $this->getParameter('billingCountry');
    }

    public function setCountry($value)
    {
        $this->setParameter('billingCountry', $value);
        $this->setParameter('shippingCountry', $value);

        return $this;
    }

    public function getPhone()
    {
        return $this->getParameter('billingPhone');
    }

    public function setPhone($value)
    {
        $this->setParameter('billingPhone', $value);
        $this->setParameter('shippingPhone', $value);

        return $this;
    }

    public function getFax()
    {
        return $this->getParameter('billingFax');
    }

    public function setFax($value)
    {
        $this->setParameter('billingFax', $value);
        $this->setParameter('shippingFax', $value);

        return $this;
    }

    public function getCompany()
    {
        return $this->getParameter('billingCompany');
    }

    public function setCompany($value)
    {
        $this->setParameter('billingCompany', $value);
        $this->setParameter('shippingCompany', $value);

        return $this;
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getBirthday($format = 'Y-m-d')
    {
        $value = $this->getParameter('birthday');

        return $value ? $value->format($format) : null;
    }

    public function setBirthday($value)
    {
        if ($value) {
            $value = new DateTime($value, new DateTimeZone('UTC'));
        } else {
            $value = null;
        }

        return $this->setParameter('birthday', $value);
    }

    public function getGender()
    {
        return $this->getParameter('gender');
    }

    public function setGender($value)
    {
        return $this->setParameter('gender', $value);
    }
}
