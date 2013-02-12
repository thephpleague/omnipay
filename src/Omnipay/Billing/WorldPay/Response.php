<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Billing\WorldPay;

use Omnipay\AbstractResponse;
use Omnipay\Exception;
use Omnipay\Exception\InvalidResponseException;

/**
 * WorldPay Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        if (empty($data['transStatus'])) {
            throw new InvalidResponseException;
        }

        $this->data = $data;
    }

    public function isSuccessful()
    {
        return 'Y' === $this->data['transStatus'];
    }

    public function getGatewayReference()
    {
        return $this->data['transId'];
    }

    public function getMessage()
    {
        return $this->data['rawAuthMessage'];
    }
}
