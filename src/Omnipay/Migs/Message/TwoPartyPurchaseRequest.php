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

/**
 * Migs Purchase Request
 */
class TwoPartyPurchaseRequest extends AbstractRequest
{
    protected $action = 'pay';

    public function getData()
    {
        $this->validate('amount', 'transactionId', 'card');

        $this->getCard()->validate();

        $data = $this->getBaseData();
        $data['vpc_CardNum'] = $this->getCard()->getNumber();
        $data['vpc_CardExp'] = $this->getCard()->getExpiryDate('ym');
        $data['vpc_CardSecurityCode'] = $this->getCard()->getCvv();
        $data['vpc_SecureHash']  = $this->calculateHash($data);

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcdps';
    }
}
