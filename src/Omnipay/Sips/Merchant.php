<?php

namespace Omnipay\Sips;

/**
 * Class Merchant
 *
 * @package Omnipay\Sips
 */
class Merchant
{
    /**
     * The Merchant id
     *
     * @var string
     */
    private $id;

    /**
     * The Merchant country
     * @var string
     */
    private $country;

    /**
     * The Merchant language
     * @var string
     */
    private $language;

    /**
     * Gets the Merchant id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the Merchant Id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the Merchant country
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Sets the Merchant country
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Gets the Merchant language
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the Merchant language
     *
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
}
