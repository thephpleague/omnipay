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
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function isSuccessful()
    {
        if (isset($this->data->Error)) {
            return false;
        }

        return 'OK' === (string) $this->data->ResponseCode;
    }

    public function getGatewayReference()
    {
        if ($this->isSuccessful()) {
            return (string) $this->data->TransactionId;
        }
    }

    public function getMessage()
    {
        if (isset($this->data->Error)) {
            return (string) $this->data->Error->Message;
        } else {
            return (string) $this->data->ResponseCode;
        }
    }
}
