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
        return $this->response = new Response($this, $this->getData());
    }

    public function getEndpoint()
    {
        return $this->endpoint.'vpcpay';
    }
}
