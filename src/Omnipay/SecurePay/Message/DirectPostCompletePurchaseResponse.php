<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SecurePay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * SecurePay Direct Post Complete Purchase Response
 */
class DirectPostCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['summarycode']) && $this->data['summarycode'] == 1;
    }

    public function getMessage()
    {
        if (isset($this->data['restext'])) {
            return $this->data['restext'];
        }
    }

    public function getCode()
    {
        if (isset($this->data['rescode'])) {
            return $this->data['rescode'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['txnid'])) {
            return $this->data['txnid'];
        }
    }
}
