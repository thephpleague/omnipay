<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;


use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class CardDeregisterFieldsDictionary
{
    /**
     * List of fields available in card deregistration
     * @var array
     */
    const REQUEST_FIELDS = array(
        /**
         * client authorization ID, sent if oneTimer option is not set
         * when creating client and client has not been deregistered (himFieldsConfigDictionary or by api)
         */
        'cli_auth'                       => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40,
                FieldsConfigDictionary::MINLENGTH_40
            ),
        ),
        /**
         * carry value of 1 if account has test mode, otherwise parameter not sent
         */
        FieldsConfigDictionary::LANGUAGE => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Message checksum
         */
        'sign'                           => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                'maxlength_128',
                FieldsConfigDictionary::MINLENGTH_40
            ),
        ),
    );

    const RESPONSE_FIELDS = array(
        /**
         * client authorization ID, sent if oneTimer option is not set
         * when creating client and client has not been deregistered (himFieldsConfigDictionary or by api)
         */
        'cli_auth'                        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40,
                FieldsConfigDictionary::MINLENGTH_40
            ),
        ),
        /**
         * carry value of 1 if account has test mode, otherwise parameter not sent
         */
        FieldsConfigDictionary::TEST_MODE => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ),
        /**
         * Date of accounting/deregistering
         */
        'date'                            => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
            FieldsConfigDictionary::FILTER     => 'date'
        ),
        /**
         * Message checksum
         */
        'sign'                            => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                'maxlength_128',
                FieldsConfigDictionary::MINLENGTH_40
            ),
        ),
    );
}
