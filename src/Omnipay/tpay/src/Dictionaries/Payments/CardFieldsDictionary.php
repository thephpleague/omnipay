<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;


use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class CardFieldsDictionary
{
    /**
     * List of supported fields for card payment request
     * @var array
     */
    const REQUEST_FIELDS = array(
        /**
         * Transaction amount
         */
        FieldsConfigDictionary::AMOUNT   => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * Client name
         */
        'name'                           => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_64
            ),
        ),
        /**
         * Client email
         */
        FieldsConfigDictionary::EMAIL    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::EMAIL_LIST
            ),
        ),
        /**
         * Sale description
         */
        'desc'                           => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
        ),
        /**
         * Value from partner system
         */
        FieldsConfigDictionary::ORDER_ID => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40
            ),
        ),
        /**
         * 3ds return url enabled
         */
        'enable_pow_url'                 => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ),
        /**
         * 3ds success return url
         */
        'pow_url'                        => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),

        'card'                        => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        'method'                        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        'sign'                        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        'api_password'                        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * 3ds failure return url
         */
        'pow_url_blad'                   => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * language
         */
        'language'                       => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Sale description
         */
        'currency'                       => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::INT,
                'maxlength_3'
            ),
        ),
        /**
         * carry value of 1 if account has test mode, otherwise parameter not sent
         */
        'onetimer'                       => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::BOOLEAN,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::BOOLEAN),
        ),
    );

    /**
     * List of fields available in card payment response
     * @var array
     */
    const RESPONSE_FIELDS = array(
        /**
         * Method type
         */
        FieldsConfigDictionary::TYPE      => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array('sale', 'refund', 'deregister'),
        ),
        /**
         * Merchant optional value
         */
        FieldsConfigDictionary::ORDER_ID  => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40
            )
        ),
        /**
         * Payment status
         */
        'status'                          => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array('correct', 'declined', 'done'),
        ),
        /**
         * Message checksum
         */
        'sign'                            => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128,
                FieldsConfigDictionary::MINLENGTH_40
            )
        ),
        /**
         * Created sale/refund id
         */
        'sale_auth'                       => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40
            )
        ),
        /**
         * Created client token
         */
        'cli_auth'                       => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_40
            )
        ),
        /**
         * Date of accounting/deregistering
         */
        'date'                            => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING)
        ),
        /**
         * carry value of 1 if account has test mode, otherwise parameter not sent
         */
        FieldsConfigDictionary::TEST_MODE => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
            FieldsConfigDictionary::OPTIONS    => array(0, 1),
        ),
        /**
         * shortcut for client card number, eg ****5678
         */
        'card'                            => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_8', 'minlength_8')
        ),
        /**
         * shortcut for client card number, eg ****5678
         */
        'amount'                          => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT)
        ),
        /**
         * payment currency
         */
        FieldsConfigDictionary::CURRENCY  => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT, 'maxlength_3', 'minlength_3')
        ),
        'reason'                          => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING)
        ),
    );
}
