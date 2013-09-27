<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\FirstDataConnect\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * FirstDataConnect Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['status']) && $this->data['status'] == 'APPROVED';
    }

    public function getTransactionReference()
    {
        return isset($this->data['oid']) ? $this->data['oid'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['status']) ? $this->data['status'] : null;
    }
}
