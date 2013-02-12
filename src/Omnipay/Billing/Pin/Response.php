<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Pin;

use Omnipay\AbstractResponse;
use Omnipay\Exception;
use Omnipay\Exception\InvalidResponseException;

/**
 * Pin Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        $this->data = json_decode($data);

        if (empty($this->data)) {
            throw new InvalidResponseException;
        }
    }

    public function isSuccessful()
    {
        return !isset($this->data->error);
    }

    public function getGatewayReference()
    {
        return $this->data->response->token;
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data->response->status_message;
        } else {
            return $this->data->error_description;
        }
    }
}
