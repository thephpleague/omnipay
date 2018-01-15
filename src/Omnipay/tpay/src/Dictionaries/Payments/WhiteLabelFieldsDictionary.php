<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;


use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class WhiteLabelFieldsDictionary
{
    /**
     * List of supported fields for white label payment request
     * @var array
     */
    const REQUEST_FIELDS = array(
        /**
         * User api login
         */
        'api_login'                    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * User api password
         */
        'api_password'                 => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Client name
         */
        'cli_name'                     => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_96'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ),
        /**
         * Client email
         */
        'cli_email'                    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
            FieldsConfigDictionary::FILTER     => 'mail'
        ),
        /**
         * Client phone
         */
        'cli_phone'                    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::MAXLENGTH_32),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::PHONE
        ),
        /**
         * Order id (payment title) the customer will be paying with; according to agreed format;
         */
        'order'                        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Transaction amount
         */
        FieldsConfigDictionary::AMOUNT => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * Message checksum
         */
        'hash'                         => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40
            ),
            FieldsConfigDictionary::FILTER     => 'sign'
        ),
    );
}
