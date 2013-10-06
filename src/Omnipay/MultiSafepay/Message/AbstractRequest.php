<?php

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $userAgent = 'Omnipay';
    protected $liveEndpoint = 'https://api.multisafepay.com/ewx/';
    protected $testEndpoint = 'https://testapi.multisafepay.com/ewx/';

    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }

    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }

    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    public function getSiteCode()
    {
        return $this->getParameter('siteCode');
    }

    public function setSiteCode($value)
    {
        return $this->setParameter('siteCode', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return array(
            'User-Agent' => $this->userAgent,
        );
    }
}
