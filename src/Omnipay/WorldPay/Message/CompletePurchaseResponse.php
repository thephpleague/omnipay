<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WorldPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * WorldPay Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['transStatus']) && 'Y' === $this->data['transStatus'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['transId']) ? $this->data['transId'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['rawAuthMessage']) ? $this->data['rawAuthMessage'] : null;
    }
}
