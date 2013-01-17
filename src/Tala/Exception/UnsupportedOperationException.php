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
 * Unsupported operation exception.
 *
 * Thrown when a gateway is asked to perform an unsupported operation.
 */
class UnsupportedOperationException extends Exception
{
}
