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

use Omnipay\Migs\ThreePartyGateway;

/**
 * GoCardless Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $endpoint = 'https://migs.mastercard.com.au/vpcpay';


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


    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['vpc_Merchant']    = $this->getMerchantId();
        $data['vpc_AccessCode']  = $this->getMerchantAccessCode();
        $data['vpc_Version']     = $this->getVersion();
        $data['vpc_Command']     = 'pay';

        return $data;
    }
    
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
