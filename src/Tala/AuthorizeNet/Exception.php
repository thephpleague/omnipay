<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\AuthorizeNet;

/**
 * Authorize.Net Exception
 *
 * Thrown when a gateway responded with invalid or unexpected data (for example, a security hash did not match).
 */
class Exception extends \RuntimeException implements \Tala\Exception
{
}
