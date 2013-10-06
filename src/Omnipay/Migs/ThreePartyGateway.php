<?php

namespace Omnipay\Migs;

/**
 * MIGS Gateway
 *
 * @link http://www.anz.com/australia/business/merchant/pdf/VPC-Dev-Kit-Integration-Notes.pdf
 */
class ThreePartyGateway extends TwoPartyGateway
{
    public function getName()
    {
        return 'MIGS 3-Party';
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Migs\Message\ThreePartyPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Migs\Message\ThreePartyCompletePurchaseRequest', $parameters);
    }
}
