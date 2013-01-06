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

use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * Authorize.Net Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        $this->data = explode('|,|', substr($data, 1, -1));

        if (count($this->data) < 10) {
            throw new InvalidResponseException();
        }

        if ($this->data[0] != '1') {
            throw new Exception($this->data[3]);
        }

        $this->gatewayReference = $this->data[6];
        $this->message = $this->data[3];
    }
}
