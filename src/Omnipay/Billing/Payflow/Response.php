<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\Payflow;

use Omnipay\AbstractResponse;
use Omnipay\Exception;
use Omnipay\Exception\InvalidResponseException;

/**
 * Payflow Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new InvalidResponseException;
        }

        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        return '0' === $this->data['RESULT'];
    }

    public function getGatewayReference()
    {
        return $this->data['PNREF'];
    }

    public function getMessage()
    {
        return $this->data['RESPMSG'];
    }
}
