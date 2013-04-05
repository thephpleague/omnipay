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
 * Migs Complete Purchase Request
 */
class ThreeCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->query->all();
        
        return $data;
    }

    public function send()
    {
        $response = new Response($this, $this->getData());

        $data = $response->getData();

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

        $this->response = $response;

        return $this->response;
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcpay';
    }
}
