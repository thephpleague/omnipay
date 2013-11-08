<?php

namespace Omnipay\PayFast\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PayFast Complete Purchase Request
 *
 * We use the same return URL & class to handle both PDT (Payment Data Transfer)
 * and ITN (Instant Transaction Notification).
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        if ($this->httpRequest->query->get('pt')) {
            // this is a Payment Data Transfer request
            $data = array();
            $data['pt'] = $this->httpRequest->query->get('pt');
            $data['at'] = $this->getPdtKey();

            return $data;
        } elseif ($signature = $this->httpRequest->request->get('signature')) {
            // this is an Instant Transaction Notification request
            $data = $this->httpRequest->request->all();

            // signature is completely useless since it has no shared secret
            // signature must not be posted back to the validate URL, so we unset it
            unset($data['signature']);

            return $data;
        }

        throw new InvalidRequestException('Missing PDT or ITN variables');
    }

    public function sendData($data)
    {
        if (isset($data['pt'])) {
            // validate PDT
            $url = $this->getEndpoint().'/query/fetch';
            $httpResponse = $this->httpClient->post($url, null, $data)->send();

            return $this->response = new CompletePurchasePdtResponse($this, $httpResponse->getBody(true));
        } else {
            // validate ITN
            $url = $this->getEndpoint().'/query/validate';
            $httpResponse = $this->httpClient->post($url, null, $data)->send();
            $status = $httpResponse->getBody(true);

            return $this->response = new CompletePurchaseItnResponse($this, $data, $status);
        }
    }
}
