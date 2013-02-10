<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\AuthorizeNet;

use Tala\AbstractResponse;
use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * Authorize.Net Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        $this->data = explode('|,|', substr($data, 1, -1));

        if (count($this->data) < 10) {
            throw new InvalidResponseException();
        }
    }

    public function isSuccessful()
    {
        return '1' === $this->data[0];
    }

    public function getGatewayReference()
    {
        return $this->data[6];
    }

    public function getMessage()
    {
        return $this->data[3];
    }
}
