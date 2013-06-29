<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid 3.0 Complete Purchase Request
 */
class RapidCompletePurchaseRequest extends RapidPurchaseRequest
{
    public function getData()
    {
        return array('AccessCode' => $this->httpRequest->query->get('AccessCode'));
    }

    public function getEndpoint()
    {
        return $this->getEndpointBase().'/GetAccessCodeResult.json';
    }
}
