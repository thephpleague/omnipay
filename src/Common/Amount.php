<?php
/**
 * Amount class
 */

namespace League\Omnipay\Common;

use InvalidArgumentException;

/**
 * Amount class
 *
 * This class abstracts certain functionality around amount and currencies in the Omnipay system.
 */
final class Amount implements AmountInterface
{
    /** @var  string */
    private $amount;

    /** @var  Currency */
    private $currency;

    /**
     * Create a new Currency object
     *
     * @param string|int $amount
     * @param string|Currency $currency
     *
     */
    public function __construct($amount, $currency)
    {
        if (( ! is_int($amount) && ! is_string($amount)) || filter_var($amount, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Amount must be a valid integer');
        }

        $this->amount = (string) $amount;
        $this->currency = self::findCurrency($currency);
    }

    /**
     * Get the amount in smallest units (eg. cents)
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the amount as decimal string
     *
     * @return string
     */
    public function getFormatted()
    {
        $decimals = $this->currency->getDecimals();
        $amount = $this->amount / pow(10, $decimals);

        return number_format(
            $amount,
            $decimals,
            '.',
            ''
        );
    }

    /**
     * Get the currency
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount < 0;
    }

    /**
     * @return bool
     */
    public function isZero()
    {
        return $this->amount == 0;
    }

    /**
     * Get the amount, based on a decimal string
     *
     * @param string|float $amount
     * @param string|Currency $currency
     * @return static
     */
    public static function fromDecimal($amount, $currency)
    {
        $amount = Helper::toFloat($amount);

        $currency = self::findCurrency($currency);
        $factor = pow(10, $currency->getDecimals());
        $amount = (int) round($amount * $factor);

        return new self($amount, $currency);
    }

    /**
     * @param string|Currency $currencyCode
     * @return Currency
     */
    private static function findCurrency($currencyCode)
    {
        if ($currencyCode instanceof Currency) {
            return $currencyCode;
        } elseif (is_string($currencyCode) || is_integer($currencyCode)) {
            $currency = Currency::find($currencyCode);
            if (is_null($currency)) {
                throw new InvalidArgumentException('Invalid currency');
            }
            return $currency;
        }

        throw new InvalidArgumentException('Currency must be a string or Currency object');
    }
}
