<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\GoCardless;

use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * GoCardless Response
 */
class Response extends \Tala\Response
{
    public function __construct($data, $resourceId)
    {
        $this->data = json_decode($data);

        if (empty($this->data->success)) {
            if (isset($this->data->error)) {
                throw new Exception(reset($this->data->error));
            }

            throw new InvalidResponseException;
        }

        $this->gatewayReference = $resourceId;
    }
}
