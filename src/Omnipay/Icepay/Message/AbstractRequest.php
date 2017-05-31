<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://connect.icepay.com/webservice/icepay.svc?wsdl';

    /**
     * @var string
     */
    protected $namespace = 'connect.icepay.com';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecretCode()
    {
        return $this->getParameter('secretCode');
    }

    public function setSecretCode($value)
    {
        return $this->setParameter('secretCode', $value);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getIssuer()
    {
        return $this->getParameter('issuer');
    }

    public function setIssuer($value)
    {
        return $this->setParameter('issuer', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('paymentMethod');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('paymentMethod', $value);
    }

    public function getTimestamp()
    {
        $timestamp = $this->getParameter('timestamp');

        if (null === $timestamp) {
            $this->setTimestamp($timestamp = gmdate("Y-m-d\TH:i:s\Z"));
        }

        return $timestamp;
    }

    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    abstract protected function generateSignature();
}
