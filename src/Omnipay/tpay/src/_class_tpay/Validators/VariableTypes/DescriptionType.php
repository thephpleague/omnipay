<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 14:39
 */
namespace Omnipay\Tpay\_class_tpay\Validators\VariableTypes;

use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Validators\VariableTypesInterface;

class DescriptionType implements VariableTypesInterface
{

    public function validateType($value, $name)
    {
        if (preg_match('/[^a-zA-Z0-9 ]/', $value) !== 0) {
            throw new TException(
                sprintf('Field "%s" contains invalid characters. Only a-z A-Z 0-9 and space', $name)
            );
        }
    }
}
