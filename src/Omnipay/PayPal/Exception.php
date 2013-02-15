<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

/**
 * Invalid Response exception.
 *
 * Thrown when a gateway responded with invalid or unexpected data (for example, a security hash did not match).
 *
 * @author  Adrian Macneil <adrian@adrianmacneil.com>
 */
class Exception extends \Omnipay\Exception
{
    public function __construct($responseData)
    {
        parent::__construct($responseData['L_LONGMESSAGE0'], $responseData['L_ERRORCODE0']);
    }
}
