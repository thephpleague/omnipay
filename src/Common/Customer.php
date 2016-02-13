<?php namespace League\Omnipay\Common;

use DateTime;
use DateTimeZone;

class Customer implements ParameterizedInterface
{
    use HasParametersTrait;

    /**
     * Create a new Customer object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct(array $parameters = [])
    {
        $this->initialize($parameters);
    }

    /**
     * Get Customer Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getParameter('title');
    }

    /**
     * Set Customer Title.
     *
     * @param string $value Parameter value
     * @return CreditCard provides a fluent interface.
     */
    public function setTitle($value)
    {
        $this->setParameter('title', $value);

        return $this;
    }

    /**
     * Get Customer First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * Set Customer First Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this provides a fluent interface.
     */
    public function setFirstName($value)
    {
        $this->setParameter('firstName', $value);

        return $this;
    }

    /**
     * Get Customer Last Name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    /**
     * Set Customer Last Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this provides a fluent interface.
     */
    public function setLastName($value)
    {
        $this->setParameter('lastName', $value);

        return $this;
    }

    /**
     * Get Customer Name.
     *
     * @return string
     */
    public function getName()
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    /**
     * Set Customer Name (Billing and Shipping).
     *
     * @param string $value Parameter value
     * @return $this provides a fluent interface.
     */
    public function setName($value)
    {
        $names = explode(' ', $value, 2);
        $this->setFirstName($names[0]);
        $this->setLastName(isset($names[1]) ? $names[1] : null);

        return $this;
    }

    /**
     * Get the address, line 1.
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->getParameter('address1');
    }

    /**
     * Sets the address, line 1.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setAddress1($value)
    {
        $this->setParameter('address1', $value);

        return $this;
    }

    /**
     * Get the address, line 2.
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->getParameter('address2');
    }

    /**
     * Sets the address, line 2.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setAddress2($value)
    {
        $this->setParameter('address2', $value);

        return $this;
    }

    /**
     * Get the city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getParameter('city');
    }

    /**
     * Sets the city.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setCity($value)
    {
        $this->setParameter('city', $value);

        return $this;
    }

    /**
     * Get the postcode.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    /**
     * Sets the postcode.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setPostcode($value)
    {
        $this->setParameter('postcode', $value);

        return $this;
    }

    /**
     * Get the state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->getParameter('state');
    }

    /**
     * Sets the state.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setState($value)
    {
        $this->setParameter('state', $value);

        return $this;
    }

    /**
     * Get the country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Sets the country.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setCountry($value)
    {
        $this->setParameter('country', $value);

        return $this;
    }

    /**
     * Get the phone number.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    /**
     * Sets the phone number.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setPhone($value)
    {
        $this->setParameter('phone', $value);

        return $this;
    }

    /**
     * Get the phone number extension.
     *
     * @return string
     */
    public function getPhoneExtension()
    {
        return $this->getParameter('phoneExtension');
    }

    /**
     * Sets the phone number extension.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setPhoneExtension($value)
    {
        $this->setParameter('phoneExtension', $value);

        return $this;
    }

    /**
     * Get the fax number..
     *
     * @return string
     */
    public function getFax()
    {
        return $this->getParameter('fax');
    }

    /**
     * Sets the fax number.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setFax($value)
    {
        $this->setParameter('fax', $value);

        return $this;
    }

    /**
     * Get the company name.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->getParameter('company');
    }

    /**
     * Sets the company name.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setCompany($value)
    {
        $this->setParameter('company', $value);

        return $this;
    }

    /**
     * Get the customer's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Sets the customer's email address.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the customer's birthday.
     *
     * @return string
     */
    public function getBirthday($format = 'Y-m-d')
    {
        $value = $this->getParameter('birthday');

        return $value ? $value->format($format) : null;
    }

    /**
     * Sets the customer's birthday.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setBirthday($value)
    {
        if ($value) {
            $value = new DateTime($value, new DateTimeZone('UTC'));
        } else {
            $value = null;
        }

        return $this->setParameter('birthday', $value);
    }

    /**
     * Get the customer's gender.
     *
     * @return string
     */
    public function getGender()
    {
        return $this->getParameter('gender');
    }

    /**
     * Sets the customer's gender.
     *
     * @param string $value
     * @return $this provides a fluent interface.
     */
    public function setGender($value)
    {
        return $this->setParameter('gender', $value);
    }
}
