<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayFast\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayFast Complete Purchase ITN Response
 */
class CompletePurchaseItnResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data, $status)
    {
        parent::__construct($request, $data);
        $this->status = $status;
    }

    public function isSuccessful()
    {
        return 'VALID' === $this->status;
    }

    public function getTransactionReference()
    {
        if ($this->isSuccessful() && isset($this->data['pf_payment_id'])) {
            return $this->data['pf_payment_id'];
        }
    }

    public function getMessage()
    {
        if ($this->isSuccessful() && isset($this->data['payment_status'])) {
            return $this->data['payment_status'];
        } else {
            return $this->status;
        }
    }
}
