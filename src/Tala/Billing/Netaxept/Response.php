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

use Tala\Exception;

/**
 * Netaxept Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        if ((string) $data->ResponseCode != 'OK') {
            throw new Exception((string) $data->ResponseCode);
        }

        $this->gatewayReference = (string) $data->TransactionId;
        $this->message = (string) $data->ResponseCode;
    }
}
