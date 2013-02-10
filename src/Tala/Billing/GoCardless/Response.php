<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\GoCardless;

use Tala\AbstractResponse;
use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * GoCardless Response
 */
class Response extends AbstractResponse
{
    protected $gatewayReference;

    public function __construct($data, $gatewayReference)
    {
        if (empty($data) or empty($gatewayReference)) {
            throw new InvalidResponseException;
        }

        $this->data = json_decode($data);
        $this->gatewayReference = $gatewayReference;
    }

    public function isSuccessful()
    {
        return !isset($this->data->error);
    }

    public function getGatewayReference()
    {
        return $this->gatewayReference;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return reset($this->data->error);
        }
    }
}
