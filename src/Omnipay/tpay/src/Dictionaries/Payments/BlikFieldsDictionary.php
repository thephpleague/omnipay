<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;

use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class BlikFieldsDictionary
{
    /**
     * List of fields available in response for blik payment
     * @var array
     */
    const ALIAS_RESPONSE_FIELDS = [
        'id'        => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ],
        'event'     => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ],
        'msg_value' => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::ARR,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::ARR),
        ],
        'md5sum'    => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ],
    ];

    /**
     * List of supported request fields for blik payment
     */
    const REQUEST_FIELDS = [
        'code'  => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT, 'maxlength_6', 'minlength_6'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::NUMBERS
        ],
        'title' => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ],
        'alias' => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::ARR),
        ]
    ];
}
