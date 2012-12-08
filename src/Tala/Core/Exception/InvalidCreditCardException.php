<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian.macneil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Core\Exception;

use Tala\Core\Exception;

/**
 * Invalid Credit Card exception.
 *
 * Thrown when a gateway responded with invalid or unexpected data (for example, a security hash did not match).
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class InvalidCreditCardException extends \RuntimeException implements Exception
{
}
