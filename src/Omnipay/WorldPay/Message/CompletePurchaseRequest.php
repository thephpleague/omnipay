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

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * WorldPay Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $callbackPW = (string) $this->httpRequest->request->get('callbackPW');
        if ($callbackPW !== $this->getCallbackPassword()) {
            throw new InvalidResponseException("Invalid callback password");
        }

        return $this->httpRequest->request->all();
    }

    public function send()
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getData());
    }
}
