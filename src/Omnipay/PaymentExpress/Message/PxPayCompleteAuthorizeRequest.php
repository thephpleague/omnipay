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

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * PaymentExpress PxPay Complete Authorize Request
 */
class PxPayCompleteAuthorizeRequest extends PxPayAuthorizeRequest
{
    public function getData()
    {
        $result = $this->httpRequest->query->get('result');
        if (empty($result)) {
            throw new InvalidResponseException;
        }

        // validate dps response
        $data = new SimpleXMLElement('<ProcessResponse/>');
        $data->PxPayUserId = $this->getUsername();
        $data->PxPayKey = $this->getPassword();
        $data->Response = $result;

        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
