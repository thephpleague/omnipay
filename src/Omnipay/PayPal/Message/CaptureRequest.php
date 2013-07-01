<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('DoCapture');

        $this->validate('transactionReference', 'amount');

        $data['AMT'] = $this->getAmount();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['AUTHORIZATIONID'] = $this->getTransactionReference();
        $data['COMPLETETYPE'] = 'Complete';

        return $data;
    }
}
