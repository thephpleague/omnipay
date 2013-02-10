<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Netaxept;

use Tala\AbstractResponse;
use Tala\Exception;

/**
 * Netaxept Response
 */
class ErrorResponse extends AbstractResponse
{
    public function __construct($message)
    {
        $this->data = $message;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function getMessage()
    {
        return $this->data;
    }
}
