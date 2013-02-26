<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Netaxept Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $data = array();
        $data['responseCode'] = $this->httpRequest->query->get('responseCode');
        $data['transactionId'] = $this->httpRequest->query->get('transactionId');
        $data['merchantId'] = $this->getMerchantId();
        $data['token'] = $this->getPassword();
        $data['operation'] = 'AUTH';

        if (empty($data['responseCode']) || empty($data['transactionId'])) {
            throw new InvalidResponseException;
        }

        return $data;
    }

    public function send()
    {
        $data = $this->getData();

        if ('OK' !== $data['responseCode']) {
            return $this->response = new ErrorResponse($this, $data);
        }

        $url = $this->getEndpoint().'/Netaxept/Process.aspx?';
        $httpResponse = $this->httpClient->get($url.http_build_query($this->getData()))->send();

        return $this->response = new Response($this, $httpResponse->xml());
    }
}
