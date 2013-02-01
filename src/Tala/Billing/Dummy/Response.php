<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Dummy;

/**
 * Dummy Response
 */
class Response extends \Tala\Response
{
    public function __construct()
    {
        $this->gatewayReference = uniqid();
    }
}
