<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Payflow\Message;

/**
 * Payflow Capture Request
 */
class CaptureRequest extends AuthorizeRequest
{
    protected $action = 'D';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data['AMT'] = $this->getAmount();
        $data['ORIGID'] = $this->getTransactionReference();

        return $data;
    }
}
