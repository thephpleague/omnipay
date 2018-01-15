<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 11:13
 */
namespace Omnipay\Tpay\_class_tpay\Validators\PaymentTypes;

use Omnipay\Tpay\_class_tpay\Validators\PaymentTypesInterface;
use Omnipay\Tpay\Dictionaries\Payments\BlikFieldsDictionary;

class PaymentTypeT6Register implements PaymentTypesInterface
{

    public function getRequestFields()
    {
        return BlikFieldsDictionary::REQUEST_FIELDS;
    }

    public function getResponseFields()
    {
        return null;
    }
}
