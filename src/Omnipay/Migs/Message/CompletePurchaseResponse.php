<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Migs\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Migs Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        parse_str($data, $this->data);
    }

    public function isSuccessful()
    {
        if (isset($this->data['vpc_Message'])) {
            if ($this->data['vpc_Message'] == "Approved") {
                return true;
            }
        }

        return false;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['vpc_ReceiptNo'])) {
            return $this->data['vpc_ReceiptNo'];
        }

        return false;
    }

    public function getMessage()
    {
        return (isset($this->data['vpc_Message']) ? $this->data['vpc_Message'] : false);
    }
}
