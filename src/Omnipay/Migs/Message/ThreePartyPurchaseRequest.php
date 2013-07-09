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
class ThreePartyPurchaseRequest extends AbstractRequest
{
    protected $action = 'pay';

    public function getData()
    {
        $this->validate('amount', 'returnUrl', 'transactionId');

        $data = $this->getBaseData();
        $data['vpc_SecureHash']  = $this->calculateHash($data);

        return $data;
    }

    public function send(array $datas = array(), $doMerge = true)
    {
        if($datas)
            $datas = $doMerge ?array_merge($this->getData(), $datas) :$datas;
        else
            $datas = $this->getData();

        $redirectUrl = $this->getEndpoint().'?'.http_build_query($datas);

        return $this->response = new ThreePartyPurchaseResponse($this, $datas, $redirectUrl);
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcpay';
    }
}
