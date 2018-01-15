<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;


use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class SzkwalFieldsDictionary
{
    /**
     * List of supported fields for szkwal payment request
     * @var array
     */
    const REQUEST_FIELDS = array(
        /**
         * User api login
         */
        'api_login'    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * User api password
         */
        'api_password' => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Client name
         */
        'cli_name'     => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_96'),
            FieldsConfigDictionary::FILTER     => 'name'
        ),
        /**
         * Client email
         */
        'cli_email'    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_128'),
            FieldsConfigDictionary::FILTER     => 'mail'
        ),
        /**
         * Client phone
         */
        'cli_phone'    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_32'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::PHONE
        ),
        /**
         * Title the client will be paying with; according to agreed format;
         */
        'title'        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Optional field sent in notifications
         */
        'crc'          => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_64'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ),
        /**
         * Client account number
         */
        'cli_account'  => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'minlength_26', 'maxlength_26'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::NUMBERS
        ),
        /**
         * Checksum
         */
        'hash'         => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MINLENGTH_40,
                FieldsConfigDictionary::MAXLENGTH_40
            ),
            FieldsConfigDictionary::FILTER     => 'sign'
        ),
    );

    /**
     * List of fields available in szkwal payment response
     * @var array
     */
    const RESPONSE_FIELDS = array(
        /**
         * Unique SZKWał payment ID
         */
        'pay_id'                       => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ),
        /**
         * Unique SZKWał notification ID
         */
        'not_id'                       => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ),
        /**
         * The title of payment in agreed format
         */
        'title'                        => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Additional client field
         */
        'crc'                          => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Transaction amount
         */
        FieldsConfigDictionary::AMOUNT => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * Message checksum
         */
        'hash'                         => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40,
                FieldsConfigDictionary::MINLENGTH_40
            ),
        ),
    );
}
