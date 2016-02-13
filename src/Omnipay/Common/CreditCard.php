<?php
/**
 * Credit Card class
 */

namespace Omnipay\Common;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Exception\InvalidCreditCardException;

/**
 * Credit Card class
 *
 * This class defines and abstracts all of the credit card types used
 * throughout the Omnipay system.
 *
 * Example:
 *
 * <code>
 *   // Define credit card parameters, which should look like this
 *   $parameters = [
 *       'firstName' => 'Bobby',
 *       'lastName' => 'Tables',
 *       'number' => '4444333322221111',
 *       'cvv' => '123',
 *       'expiryMonth' => '12',
 *       'expiryYear' => '2017',
 *       'email' => 'testcard@gmail.com',
 *   ];
 *
 *   // Create a credit card object
 *   $card = new CreditCard($parameters);
 * </code>
 *
 * The full list of card attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * title
 * * firstName
 * * lastName
 * * name
 * * company
 * * address1
 * * address2
 * * city
 * * postcode
 * * state
 * * country
 * * phone
 * * phoneExtension
 * * fax
 * * number
 * * expiryMonth
 * * expiryYear
 * * startMonth
 * * startYear
 * * cvv
 * * issueNumber
 * * billingTitle
 * * billingName
 * * billingFirstName
 * * billingLastName
 * * billingCompany
 * * billingAddress1
 * * billingAddress2
 * * billingCity
 * * billingPostcode
 * * billingState
 * * billingCountry
 * * billingPhone
 * * billingFax
 * * shippingTitle
 * * shippingName
 * * shippingFirstName
 * * shippingLastName
 * * shippingCompany
 * * shippingAddress1
 * * shippingAddress2
 * * shippingCity
 * * shippingPostcode
 * * shippingState
 * * shippingCountry
 * * shippingPhone
 * * shippingFax
 * * email
 * * birthday
 * * gender
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class CreditCard implements ParameterizedInterface
{
    use HasParametersTrait;

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
     * All known/supported card brands, and a regular expression to match them.
     *
     * The order of the card brands is important, as some of the regular expressions overlap.
     *
     * Note: The fact that a particular card brand has been added to this array does not imply
     * that a selected gateway will support the card.
     *
     * @link https://github.com/Shopify/active_merchant/blob/master/lib/active_merchant/billing/credit_card_methods.rb
     * @var array
     */
    protected $supported_cards = [
        self::BRAND_VISA => '/^4\d{12}(\d{3})?$/',
        self::BRAND_MASTERCARD => '/^(5[1-5]\d{4}|677189)\d{10}$/',
        self::BRAND_DISCOVER => '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/',
        self::BRAND_AMEX => '/^3[47]\d{13}$/',
        self::BRAND_DINERS_CLUB => '/^3(0[0-5]|[68]\d)\d{11}$/',
        self::BRAND_JCB => '/^35(28|29|[3-8]\d)\d{12}$/',
        self::BRAND_SWITCH => '/^6759\d{12}(\d{2,3})?$/',
        self::BRAND_SOLO => '/^6767\d{12}(\d{2,3})?$/',
        self::BRAND_DANKORT => '/^5019\d{12}$/',
        self::BRAND_MAESTRO => '/^(5[06-8]|6\d)\d{10,17}$/',
        self::BRAND_FORBRUGSFORENINGEN => '/^600722\d{10}$/',
        self::BRAND_LASER => '/^(6304|6706|6709|6771(?!89))\d{8}(\d{4}|\d{6,7})?$/',
    ];

    /**
     * @var Customer
     */
    private $shippingCustomer;

    /**
     * @var Customer
     */
    private $billingCustomer;

    /**
     * Create a new CreditCard object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct(array $parameters = [])
    {
        $this->initialize($parameters);
    }

    /**
     * All known/supported card brands, and a regular expression to match them.
     *
     * Note: The fact that this class knows about a particular card brand does not imply
     * that your gateway supports it.
     *
     * @see self::$supported_cards
     * @return array
     */
    public function getSupportedBrands()
    {
        return $this->supported_cards;
    }

    /**
     * Set a custom supported card brand with a regular expression to match it.
     *
     * Note: The fact that a particular card is known does not imply that your
     * gateway supports it.
     *
     * Set $add_to_front to true if the key should be added to the front of the array
     *
     * @param  string  $name The name of the new supported brand.
     * @param  string  $expression The regular expression to check if a card is supported.
     * @return boolean success
     */
    public function addSupportedBrand($name, $expression)
    {
        $known_brands = array_keys($this->supported_cards);

        if (in_array($name, $known_brands)) {
            return false;
        }

        $this->supported_cards[$name] = $expression;

        return true;
    }

    /**
     * Set the credit card year.
     *
     * The input value is normalised to a 4 digit number.
     *
     * @param string $key Parameter key, e.g. 'expiryYear'
     * @param mixed $value Parameter value
     * @return CreditCard provides a fluent interface.
     */
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
     *
     * @throws InvalidCreditCardException
     * @return void
     */
    public function validate()
    {
        foreach (['number', 'expiryMonth', 'expiryYear'] as $key) {
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

        if (!is_null($this->getNumber()) && !preg_match('/^\d{12,19}$/i', $this->getNumber())) {
            throw new InvalidCreditCardException('Card number should have 12 to 19 digits');
        }
    }

    /**
     * Get Card Number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->getParameter('number');
    }

    /**
     * Set Card Number
     *
     * Non-numeric characters are stripped out of the card number, so
     * it's safe to pass in strings such as "4444-3333 2222 1111" etc.
     *
     * @param string $value Parameter value
     * @return CreditCard provides a fluent interface.
     */
    public function setNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('number', preg_replace('/\D/', '', $value));
    }

    /**
     * Get the last 4 digits of the card number.
     *
     * @return string
     */
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

    /**
     * Get the card expiry month.
     *
     * @return string
     */
    public function getExpiryMonth()
    {
        return $this->getParameter('expiryMonth');
    }

    /**
     * Sets the card expiry month.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setExpiryMonth($value)
    {
        return $this->setParameter('expiryMonth', (int) $value);
    }

    /**
     * Get the card expiry year.
     *
     * @return string
     */
    public function getExpiryYear()
    {
        return $this->getParameter('expiryYear');
    }

    /**
     * Sets the card expiry year.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
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

    /**
     * Get the card start month.
     *
     * @return string
     */
    public function getStartMonth()
    {
        return $this->getParameter('startMonth');
    }

    /**
     * Sets the card start month.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setStartMonth($value)
    {
        return $this->setParameter('startMonth', (int) $value);
    }

    /**
     * Get the card start year.
     *
     * @return string
     */
    public function getStartYear()
    {
        return $this->getParameter('startYear');
    }

    /**
     * Sets the card start year.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
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

    /**
     * Get the card CVV.
     *
     * @return string
     */
    public function getCvv()
    {
        return $this->getParameter('cvv');
    }

    /**
     * Sets the card CVV.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setCvv($value)
    {
        return $this->setParameter('cvv', $value);
    }

    /**
     * Get the card issue number.
     *
     * @return string
     */
    public function getIssueNumber()
    {
        return $this->getParameter('issueNumber');
    }

    /**
     * Sets the card issue number.
     *
     * @param string $value
     * @return CreditCard provides a fluent interface.
     */
    public function setIssueNumber($value)
    {
        return $this->setParameter('issueNumber', $value);
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->setBillingCustomer($customer);
        $this->setShippingCustomer($customer);
    }

    /**
     * @param Customer $customer
     */
    private function setBillingCustomer(Customer $customer)
    {
        $this->billingCustomer = $customer;
    }

    /**
     * @param Customer $customer
     */
    private function setShippingCustomer(Customer $customer)
    {
        $this->shippingCustomer = $customer;
    }

    /**
     * @return Customer
     */
    public function getShippingCustomer()
    {
        return $this->shippingCustomer;
    }

    /**
     * @return Customer
     */
    public function getBillingCustomer()
    {
        return $this->billingCustomer;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->getBillingCustomer();
    }
}
