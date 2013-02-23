<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe\Message;

/**
 * Stripe Refund Request
 */
class RefundRequest extends PurchaseRequest
{
    public function getData()
    {
        $this->validate(array('gatewayReference', 'amount'));

        $data = array();
        $data['amount'] = $this->getAmount();

        return $data;
    }

    public function getUrl()
    {
        return '/charges/'.$this->getGatewayReference().'/refund';
    }

    public function createResponse($gatewayReference)
    {
        return new Response($gatewayReference);
    }
}
