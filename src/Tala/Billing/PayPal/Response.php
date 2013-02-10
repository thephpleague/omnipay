<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\PayPal;

use Tala\AbstractResponse;

/**
 * PayPal Express Class
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return true;
    }

    public function getGatewayReference()
    {
        foreach (array('REFUNDTRANSACTIONID', 'TRANSACTIONID', 'PAYMENTINFO_0_TRANSACTIONID') as $key) {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }
        }
    }
}
