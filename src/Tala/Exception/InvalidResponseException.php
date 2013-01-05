<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Exception;

use Tala\Exception;

/**
 * Invalid Response exception.
 *
 * Thrown when a gateway responded with invalid or unexpected data (for example, a security hash did not match).
 */
class InvalidResponseException extends \RuntimeException implements Exception
{
    public function __construct($message = "Invalid response from payment gateway", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
