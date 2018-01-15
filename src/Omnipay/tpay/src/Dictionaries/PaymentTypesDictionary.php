<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries;

class PaymentTypesDictionary
{
    const PAYMENT_TYPE_BASIC = 'Basic';
    const PAYMENT_TYPE_BASIC_API = 'BasicApi';
    const PAYMENT_TYPE_CARD = 'Card';
    const PAYMENT_TYPE_SZKWAL = 'Szkwal';
    const PAYMENT_TYPE_WHITE_LABEL = 'WhiteLabel';
    const PAYMENT_TYPE_EHAT = 'Ehat';
    const PAYMENT_TYPE_SMS = 'Sms';
    const PAYMENT_TYPE_BLIK_T6STANDARD = 'T6Standard';
    const PAYMENT_TYPE_BLIK_T6REGISTER = 'T6Register';
    const PAYMENT_TYPE_BLIK_ALIAS = 'BlikAlias';
    const CARD_DEREGISTER = 'Deregister';
    const UNKNOWN_PAYMENT_TYPE = 'Unknown payment type ';
}
