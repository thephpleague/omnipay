<?php

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
