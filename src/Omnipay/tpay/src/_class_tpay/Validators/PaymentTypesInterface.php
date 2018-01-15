<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 11:09
 */

namespace Omnipay\Tpay\_class_tpay\Validators;

interface PaymentTypesInterface
{

    public function getRequestFields();

    public function getResponseFields();

}
