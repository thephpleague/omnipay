<?php

/*
 * Created by tpay.com.
 * Date: 16.06.2017
 * Time: 11:23
 */

namespace Omnipay\Tpay\Dictionaries\ErrorCodes;

class SzkwalErrors
{
    /**
     * The list of possible errors returning from tpay servive
     * @var array
     */
    const ERROR_CODES = array(
        'ERR01' => 'authorization failed',
        'ERR02' => 'required input empty',
        'ERR03' => 'incorrect title format',
        'ERR04' => 'title busy',
        'ERR05' => 'incorrect hash',
        'ERR06' => 'no such client',
        'ERR07' => 'malformed CSV',
        'ERR08' => 'no such package',
        'ERR09' => 'incorrect host',
        'ERR10' => 'incorrect email',
        'ERR11' => 'incorrect dates',
        'ERR12' => 'incorrect amount',
        'ERR13' => 'no such method',
        'ERR14' => 'Insufficient funds',
        'ERR15' => 'Incorrect client account number',
        'ERR99' => 'other error',
    );
}
