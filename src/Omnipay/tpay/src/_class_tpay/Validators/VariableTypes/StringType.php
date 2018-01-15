<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 14:39
 */
namespace Omnipay\Tpay\_class_tpay\Validators\VariableTypes;

use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Validators\VariableTypesInterface;

class StringType implements VariableTypesInterface
{

    public function validateType($value, $name)
    {
        if (!is_string($value)) {
            throw new TException(sprintf('Field "%s" must be a string, type given: ' . gettype($value), $name));
        }
    }
}
