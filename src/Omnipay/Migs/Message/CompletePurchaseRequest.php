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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Migs\ThreePartyGateway;

/**
 * Migs Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {

        $data = http_build_query($this->httpRequest->query->all());
        return $data;
    }

    public function send()
    {
        return $this->response = new CompletePurchaseResponse($this, $this->getData());
    }
}
