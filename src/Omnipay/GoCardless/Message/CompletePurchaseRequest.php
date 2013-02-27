<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\GoCardless\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\GoCardless\Gateway;

/**
 * GoCardless Complete Purchase Request
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array();
        $data['resource_uri'] = $this->httpRequest->get('resource_uri');
        $data['resource_id'] = $this->httpRequest->get('resource_id');
        $data['resource_type'] = $this->httpRequest->get('resource_type');

        if ($this->generateSignature($data) !== $this->httpRequest->get('signature')) {
            throw new InvalidResponseException;
        }

        unset($data['resource_uri']);

        return $data;
    }

    public function send()
    {
        $httpRequest = $this->httpClient->post(
            $this->getEndpoint().'/api/v1/confirm',
            array('Accept' => 'application/json'),
            Gateway::generateQueryString($this->getData())
        );
        $httpResponse = $httpRequest->setAuth($this->getAppId(), $this->getAppSecret())->send();

        return $this->response = new CompletePurchaseResponse(
            $this,
            $httpResponse->json(),
            $this->httpRequest->get('resource_id')
        );
    }
}
