<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Common\Exception;

/**
 * Invalid Credit Card Exception
 *
 * Thrown when a credit card is invalid or missing required fields.
 */
class InvalidCreditCardException extends \Exception implements OmnipayException
{
}
