<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 11:13
 */
namespace Omnipay\Tpay\_class_tpay\Validators\PaymentTypes;

use Omnipay\Tpay\_class_tpay\Validators\PaymentTypesInterface;
use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;
use Omnipay\Tpay\Dictionaries\Payments\BlikFieldsDictionary;

class PaymentTypeT6Standard implements PaymentTypesInterface
{

    public function getRequestFields()
    {
        $fields = BlikFieldsDictionary::REQUEST_FIELDS;
        $fields['alias'][FieldsConfigDictionary::REQUIRED] = false;
        return $fields;
    }

    public function getResponseFields()
    {
        return null;
    }
}
