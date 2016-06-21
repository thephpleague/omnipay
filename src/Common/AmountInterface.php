<?php
/**
 * Amount interface
 */

namespace League\Omnipay\Common;

use InvalidArgumentException;

/**
 * Amount class
 *
 * This class abstracts certain functionality around amount and currencies in the Omnipay system.
 */
interface AmountInterface
{
    /**
     * Get the amount in smallest units (eg. cents)
     *
     * @return string
     */
    public function getAmount();

    /**
     * Get the amount as decimal string
     *
     * @return string
     */
    public function getFormatted();

    /**
     * Get the currency
     *
     * @return Currency
     */
    public function getCurrency();

    /**
     * @return bool
     */
    public function isNegative();

    /**
     * @return bool
     */
    public function isZero();
}
