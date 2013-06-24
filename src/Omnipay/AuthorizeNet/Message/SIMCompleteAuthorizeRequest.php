<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Authorize.Net SIM Complete Authorize Request
 */
class SIMCompleteAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        if (strtolower($this->httpRequest->request->get('x_MD5_Hash')) !== $this->getHash()) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $this->httpRequest->request->all();
    }

    public function getHash()
    {
        return md5($this->getApiLoginId().$this->getTransactionId().$this->getAmount());
    }

    public function send()
    {
        return $this->response = new SIMCompleteAuthorizeResponse($this, $this->getData());
    }
}
