<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\Common\AbstractResponse;
use Omnipay\Exception;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Stripe Response
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
        return $this->data->id;
    }

    public function getMessage()
    {
        if (!$this->isSuccessful()) {
            return $this->data->error->message;
        }
    }
}
