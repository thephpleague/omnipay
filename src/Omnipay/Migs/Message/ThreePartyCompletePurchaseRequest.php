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
class ThreePartyCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->query->all();

        $hash = isset($data['vpc_SecureHash']) ? $data['vpc_SecureHash'] : null;
        if ($this->calculateHash($data) !== $hash) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $data;
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if($datas)
            $datas = $doMerge ?array_merge($this->getData(), $datas) :$datas;
        else
            $datas = $this->getData();

        return $this->response = new Response($this, $datas);
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcpay';
    }
}
