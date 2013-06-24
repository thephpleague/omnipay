<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Capture Request
 */
class PxPostCaptureRequest extends PxPostAuthorizeRequest
{
    protected $action = 'Complete';

    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = $this->getBaseData();
        $data->DpsTxnRef = $this->getTransactionReference();
        $data->Amount = $this->getAmount();

        return $data;
    }
}
