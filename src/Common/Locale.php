<?php
/**
 * Locale class
 */

namespace League\Omnipay\Common;

use InvalidArgumentException;

/**
 * Locale class
 *
 * This class abstracts certain functionality around locales in the Omnipay system.
 */
final class Locale
{
    /** @var  string */
    private $primaryLanguage;

    /** @var  string */
    private $region;

    /**
     * Create a new Currency object
     *
     * @param string $primaryLanguage
     * @param string $region
     *
     */
    public function __construct($primaryLanguage, $region = null)
    {
        $this->primaryLanguage = strtolower($primaryLanguage);
        $this->region = strtolower($region);
    }

    /**
     * Get the full locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->primaryLanguage . ($this->region ? '-' . $this->region : '');
    }

    /**
     * Get the primary language
     *
     * @return string
     */
    public function getPrimaryLanguage()
    {
        return $this->primaryLanguage;
    }

    /**
     * Get the region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region ?: null;
    }

    /**
     * Get the locale, based on a string
     *
     * @param string $locale
     * @return static
     */
    public static function parse($locale)
    {
        $primaryLanguage = $locale;
        $region = null;

        $locale = str_replace('_', '-', $locale);
        if (strpos($locale, '-') !== false) {
            list($primaryLanguage, $region) = explode('-', $locale);
        }

        return new static($primaryLanguage, $region);
    }

    public function __toString()
    {
        return $this->getLocale();
    }
}
