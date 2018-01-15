<?php

/*
 * Created by tpay.com.
 * Date: 13.06.2017
 * Time: 17:05
 */

namespace Omnipay\Tpay\Dictionaries\Payments;


use Omnipay\Tpay\Dictionaries\FieldsConfigDictionary;

class StandardFieldsDictionary
{
    /**
     * List of supported request fields for basic payment
     */

    const REQUEST_FIELDS = [
        /**
         * Transaction amount with dot as decimal separator.
         */
        'kwota'               => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ],
        /**
         * Transaction description
         */
        'opis'                => [
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ],
        /**
         * The secondary parameter to the transaction identification.
         * After the transaction returned as a parameter tr_crc.
         */
        'crc'                 => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
        ],
        /**
         * Allow only online payment.
         * Prevents the channel selection, which at the moment is not able to post real-time payment.
         */
        'online'              => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array(0, 1),
        ],
        /**
         * Imposing the customer the pre-payment channel.
         * Could be changed manually by customer.
         * Required for register transaction by transaction API
         * Customer will be presented only the selected group.
         */
        'grupa'               => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ],
        /**
         * If this parameter is set, the payer will always be redirected to step 2 in tpay.com panel
         */
        'wybor' => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array(0, 1),
        ],
        /**
         * Customer will be redirected to bank login page.
         */
        'direct'              => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::INT),
        ],
        /**
         * The resulting URL return address that will send the result of a transaction in the form POST parameters.
         */
        'wyn_url'             => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_512
            ),
            FieldsConfigDictionary::FILTER     => 'url'
        ],
        /**
         * E-mail address to which you will be notified about the status of the transaction.
         */
        'wyn_email'           => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::EMAIL_LIST),
        ],
        /**
         * Description payees during the transaction.
         */
        'opis_sprzed'         => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ],
        /**
         * Optional field used during card transactions processed through Elavon.
         * The value of the field is passed to Elavon as „TEKST REF. TRANSAKCJI”.
         * Acceptable characters are a-z, AZ (without Polish), 0-9 and space.
         * All others will be removed.
         * Max 32 signs.
         */
        'opis_dodatkowy'      => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::OPIS_DODATKOWY,
                FieldsConfigDictionary::MAXLENGTH_32
            ),
            FieldsConfigDictionary::FILTER     => 'mixed'
        ],
        /**
         * The URL to which the customer will be transferred after successful completion of the transaction.
         */
        'pow_url'             => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_512
            ),
            FieldsConfigDictionary::FILTER     => 'url'
        ],
        /**
         * The URL to which the client will be transferred in the event of an error.
         * Default is pow_url
         */
        'pow_url_blad'        => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_512
            ),
            FieldsConfigDictionary::FILTER     => 'url'
        ],
        /**
         * Transactional panel language.
         * Default is PL
         */
        'jezyk'               => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array('PL', 'EN', 'DE', 'IT', 'ES', 'FR', 'RU'),
        ],
        /**
         * Customer email address.
         */
        'email'               => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_64
            ),
            FieldsConfigDictionary::FILTER     => 'mail'
        ],
        /**
         * Customer surname.
         */
        'nazwisko'            => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_64
            ),
            FieldsConfigDictionary::FILTER     => 'name'
        ],
        /**
         * Customer name.
         */
        'imie'                => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_64
            ),
            FieldsConfigDictionary::FILTER     => 'name'
        ],
        /**
         * Customer address.
         */
        'adres'               => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_64
            ),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ],
        /**
         * Customer city.
         */
        'miasto'              => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_32
            ),
            FieldsConfigDictionary::FILTER     => 'name'
        ],
        /**
         * Customer postal code.
         */
        'kod'                 => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_10'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::TEXT
        ],
        /**
         * Country code.
         * Alphanumeric, 2 or 3 signs compatible with ISO 3166-1
         */
        'kraj'                => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array('country_code'),
        ],
        /**
         * Customer phone.
         */
        'telefon'             => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_16'),
            FieldsConfigDictionary::FILTER     => FieldsConfigDictionary::PHONE
        ],
        /**
         * The parameter indicating acceptance of Terms tpay if it is available on the payee.
         */
        'akceptuje_regulamin' => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array(0, 1),
        ],
        /**
         * Description payees during the transaction.
         */
        'expiration_date'         => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
        ],
        /**
         * Description payees during the transaction.
         */
        'timehash'         => [
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::VALIDATION => array(
                FieldsConfigDictionary::STRING,
                FieldsConfigDictionary::MAXLENGTH_128
            ),
        ],
    ];

    const RESPONSE_FIELDS = array(
        /**
         * The merchant ID assigned by the system tpay
         */
        'id'        => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * The transaction ID assigned by the system tpay
         */
        'tr_id'     => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Date of transaction.
         */
        'tr_date'   => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * The secondary parameter to the transaction identification.
         */
        'tr_crc'    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Transaction amount.
         */
        'tr_amount' => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * Transaction amount.
         */
        'tr_channel' => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * The amount paid for the transaction.
         * Note: Depending on the settings, the amount paid can be different
         * than transactions eg. When the customer does overpayment.
         */
        'tr_paid'   => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::FLOAT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::FLOAT),
        ),
        /**
         * Description of the transaction.
         */
        'tr_desc'   => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING),
        ),
        /**
         * Transaction status: TRUE in the case of the correct result or FALSE in the case of an error.
         * Note: Depending on the settings, the transaction may be correct status,
         * even if the amount paid is different from the amount of the transaction!
         * Eg. If the Seller accepts the overpayment or underpayment threshold is set.
         */
        'tr_status' => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array(0, 1, true, false, 'TRUE', 'FALSE'),
        ),
        /**
         * Transaction error status.
         * Could have the following values:
         * - none
         * - overpay
         * - surcharge
         */
        'tr_error'  => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array('none', 'overpay', 'surcharge'),
        ),
        /**
         * Customer email address.
         */
        'tr_email'  => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::EMAIL_LIST),
        ),
        /**
         * The checksum verifies the data sent to the payee.
         * It is built according to the following scheme using the MD5 hash function:
         * MD5(id + tr_id + tr_amount + tr_crc + security code)
         */
        'md5sum'    => array(
            FieldsConfigDictionary::REQUIRED   => true,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::STRING,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::STRING, 'maxlength_32', 'minlength_32'),
        ),
        /**
         * Transaction marker indicates whether the transaction was executed in test mode:
         * 1 – in test mode
         * 0 – in normal mode
         */
        'test_mode' => array(
            FieldsConfigDictionary::REQUIRED   => false,
            FieldsConfigDictionary::TYPE       => FieldsConfigDictionary::INT,
            FieldsConfigDictionary::VALIDATION => array(FieldsConfigDictionary::OPTIONS),
            FieldsConfigDictionary::OPTIONS    => array(0, 1),
        ),
        /**
         * The parameter is sent only when you use a payment channel or MasterPass or V.me.
         * Could have the following values: „masterpass” or „vme”
         */
        'wallet'    => array(
            FieldsConfigDictionary::REQUIRED => false,
            FieldsConfigDictionary::TYPE     => FieldsConfigDictionary::STRING,
        ),
    );
}
