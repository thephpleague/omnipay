<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 12:51
 */

namespace Omnipay\Tpay\_class_tpay\Validators;

use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Validators\VariableTypes\StringType;

trait AccessConfigValidator
{
    /**
     * FieldsConfigValidator card verification code
     *
     * @param string $cardCode
     *
     * @throws TException
     */
    public static function validateCardCode($cardCode)
    {
        if (!is_string($cardCode) || strlen($cardCode) === 0 || strlen($cardCode) > 40) {
            throw new TException('Invalid card code');
        }
    }

    /**
     * FieldsConfigValidator card hash algorithm
     * @param string $hashAlg
     * @throws TException
     */
    public static function validateCardHashAlg($hashAlg)
    {
        if (!in_array($hashAlg, array('sha1', 'sha256', 'sha512', 'ripemd160', 'ripemd320', 'md5'))) {
            throw new TException('Invalid hash algorithm');
        }
    }

    /**
     * FieldsConfigValidator merchant Id
     *
     * @param int $merchantId
     *
     * @throws TException
     */
    public function validateMerchantId($merchantId)
    {
        if (!is_int($merchantId) || $merchantId <= 0) {
            throw new TException('Invalid merchant ID');
        }
    }

    public function isNotEmptyString($string, $name)
    {
        (new StringType())->validateType($string, $name);
        $this->validateMinLength($string, 'minlength_1', $name);
    }
}
