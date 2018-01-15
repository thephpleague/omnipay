<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 14:39
 */
namespace Omnipay\Tpay\_class_tpay\Validators\VariableTypes;

use Omnipay\Tpay\_class_tpay\Utilities\TException;
use Omnipay\Tpay\_class_tpay\Validators\VariableTypesInterface;

class EmailListType implements VariableTypesInterface
{

    public function validateType($value, $name)
    {
        if (!is_string($value)) {
            throw new TException(sprintf('Field "%s" must be a string', $name));
        }
        $emails = explode(',', $value);
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false && strlen($email) > 0) {
                throw new TException(
                    sprintf('Field "%s" contains invalid email address', $name)
                );
            }
        }
    }
}
