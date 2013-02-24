<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\TwoCheckout\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * 2Checkout Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $orderNo = $this->httpRequest->request->get('order_number');

        // strange exception specified by 2Checkout
        if ($this->testMode) {
            $orderNo = '1';
        }

        $key = md5($this->secretWord.$this->accountNumber.$orderNo.$this->getAmountDecimal());
        if (strtolower($this->httpRequest->request->get('key')) !== $key) {
            throw new InvalidResponseException('Invalid key');
        }

        return $this->httpRequest->request->all();
    }

    public function createResponse($data)
    {
        return new CompletePurchaseResponse($data);
    }
}
