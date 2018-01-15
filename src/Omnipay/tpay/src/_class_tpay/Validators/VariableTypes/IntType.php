<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 14:39
 */
namespace Omnipay\Tpay\_class_tpay\Validators\VariableTypes;

use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Validators\VariableTypesInterface;

class IntType implements VariableTypesInterface
{

    public function validateType($value, $name)
    {
        if (!is_int($value)) {
            throw new TException(sprintf('Field "%s" must be an integer', $name));
        } else {
            if ($value <= 0) {
                throw new TException(sprintf('Field "%s" must be higher than zero', $name));
            }
        }
    }
}
