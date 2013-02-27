<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Netaxept Response
 */
class ErrorResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return false;
    }

    public function getTransactionReference()
    {
        return $this->data['transactionId'];
    }

    public function getMessage()
    {
        return $this->data['responseCode'];
    }
}
