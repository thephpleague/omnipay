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
 * Bad method call exception.
 *
 * @author  Adrian Macneil <adrian.macneil@gmail.com>
 */
class BadMethodCallException extends \BadMethodCallException implements Exception
{
}
