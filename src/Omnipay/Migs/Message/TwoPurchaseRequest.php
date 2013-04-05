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

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Migs Purchase Request
 */
class TwoPurchaseRequest extends AbstractRequest
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


        // we need to sort parameters a-z for the gateway

        ksort($data);

        $data['vpc_SecureHash']  = $this->getHash($data);

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        $data = $httpResponse->getBody();

        if(!is_array($data))
        {
            parse_str($data, $data);
        }

        if(!isset($data['vpc_SecureHash']))
        {
            throw new InvalidRequestException('Incorrect hash');
        }

        $secureHash = $data['vpc_SecureHash'];

        $calculatedHash = $this->getHash($data);

        if($secureHash != $calculatedHash) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $this->response = new Response($this, $data);
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcdps';
    }
}
