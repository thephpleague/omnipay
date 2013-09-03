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
use SoapClient;

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
        return $this->getParameter('timestamp');
    }

    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    /**
     * @return SoapClient
     *
     * @todo this has to go
     */
    protected function getSoapClient()
    {
        return new SoapClient($this->endpoint, array(
            'location' => $this->endpoint,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => true,
        ));
    }

    abstract protected function generateSignature();
}
