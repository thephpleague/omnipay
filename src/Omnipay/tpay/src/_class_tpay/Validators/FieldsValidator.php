<?php

/*
 * Created by tpay.com.
 * Date: 12.06.2017
 * Time: 17:39
 */

namespace Omnipay\Tpay\_class_tpay\Validators;


use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\Dictionaries\ISO_codes\CurrencyCodesDictionary;
use Omnipay\Tpay\Dictionaries\Localization\CardPaymentLanguagesDictionary;

trait FieldsValidator
{
    /**
     * FieldsConfigValidator card currency code
     *
     * @param string $currency
     *
     * @throws TException
     * @return int
     */
    public function validateCardCurrency($currency)
    {
        if (strlen($currency) !== 3) {
            throw new TException('Currency is invalid.');
        }

        switch (gettype($currency)) {
            case 'string':
                if (in_array($currency, CurrencyCodesDictionary::CODES)) {
                    $currency = array_search($currency, CurrencyCodesDictionary::CODES);
                } elseif (array_key_exists((int)$currency, CurrencyCodesDictionary::CODES)) {
                    $currency = (int)$currency;
                } else {
                    throw new TException('Currency is not supported.');
                }

                break;
            case 'integer':
                if (!array_key_exists($currency, CurrencyCodesDictionary::CODES)) {
                    throw new TException('Currency is not supported.');
                }
                break;
            default:
                throw new TException('Currency variable type not supported.');
        }
        return $currency;

    }

    /**
     * FieldsConfigValidator card payment language
     *
     * @param string $language
     *
     * @throws TException
     * @return string
     */
    public function validateCardLanguage($language)
    {
        if (!is_string($language)) {
            throw new TException('Invalid language value type.');
        }
        if (in_array($language, CardPaymentLanguagesDictionary::LANGUAGES)) {
            return CardPaymentLanguagesDictionary::LANGUAGES[array_search($language,
                CardPaymentLanguagesDictionary::LANGUAGES)];
        } elseif (!array_key_exists($language, CardPaymentLanguagesDictionary::LANGUAGES)) {
            return 'en';
        } else {
            return $language;
        }

    }

    /**
     * Check if variable has expected value
     *
     * @param mixed $value variable to check
     * @param array $options available options
     * @param string $name field name
     *
     * @throws TException
     */
    protected function validateOptions($value, $options, $name)
    {
        if (!in_array($value, $options, true)) {
            throw new TException(sprintf('Field "%s" has unsupported value', $name));
        }
    }

    /**
     * Check variable max length
     *
     * @param mixed $value variable to check
     * @param $validator
     * @param string $name field name
     *
     * @throws TException
     * @internal param int $max max length
     */
    protected function validateMaxLength($value, $validator, $name)
    {
        $max = explode('_', $validator);
        $max = (int)$max[1];
        if (strlen($value) > $max) {
            throw new TException(
                sprintf('Value of field "%s" is too long. Max %d characters', $name, $max)
            );
        }
    }

    /**
     * Check variable min length
     *
     * @param mixed $value variable to check
     * @param $validator
     * @param string $name field name
     *
     * @throws TException
     * @internal param int $min min length
     */
    protected function validateMinLength($value, $validator, $name)
    {
        $min = explode('_', $validator);
        $min = (int)$min[1];
        if (strlen($value) < $min) {
            throw new TException(
                sprintf('Value of field "%s" is too short. Min %d characters', $name, $min)
            );
        }
    }

    /**
     * Check if giver parameter is number
     * @param $number
     * @return bool
     * @throws TException
     */
    protected function validateNumeric($number)
    {
        if (is_numeric($number)) {
            return true;
        }
        throw new TException(sprintf('Value "%s" is not numeric.', $number));
    }

}
