<?php

namespace Omnipay\Migs;

use Omnipay\Common\AbstractGateway;

/**
 * MIGS Gateway
 *
 * @link http://www.anz.com/australia/business/merchant/pdf/VPC-Dev-Kit-Integration-Notes.pdf
 */
class TwoPartyGateway extends AbstractGateway
{
    public function getName()
    {
        return 'MIGS 2-Party';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId'                   => '',
            'merchantAccessCode'           => '',
            'secureHash'                   => ''
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantAccessCode()
    {
        return $this->getParameter('merchantAccessCode');
    }

    public function setMerchantAccessCode($value)
    {
        return $this->setParameter('merchantAccessCode', $value);
    }

    public function getSecureHash()
    {
        return $this->getParameter('secureHash');
    }

    public function setSecureHash($value)
    {
        return $this->setParameter('secureHash', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Migs\Message\TwoPartyPurchaseRequest', $parameters);
    }
}
