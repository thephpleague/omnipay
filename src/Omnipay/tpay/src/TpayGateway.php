<?php

namespace Omnipay\Tpay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\BadMethodCallException;
use Omnipay\Common\GatewayInterface;
use Omnipay\Tpay\Message\AuthorizeRequest;
use Omnipay\Tpay\Message\CaptureRequest;
use Omnipay\Tpay\Message\CompletePurchaseRequest;
use Omnipay\Tpay\Message\DeregisterRequest;
use Omnipay\Tpay\Message\PurchaseRequest;
use Omnipay\Tpay\Message\RefundRequest;

class TpayGateway extends AbstractGateway implements GatewayInterface
{
    protected $supportedLanguages = array('pl', 'en', 'fr', 'es', 'it', 'ru');

    public function getName()
    {
        return 'Tpay.com';
    }

    public function getDefaultParameters()
    {
        return array_merge(parent::getDefaultParameters(), array(
            'apiKey' => '',
            'apiPassword' => '',
            'verificationCode' => '',
            'rsaKey' => '',
            'hashType' => 'sha1',
            'currentDomain' => 'http://localhost',
            'language' => 'en',
        ));
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getApiPassword()
    {
        return $this->getParameter('apiPassword');
    }

    public function setApiPassword($value)
    {
        return $this->setParameter('apiPassword', $value);
    }

    public function getVerificationCode()
    {
        return $this->getParameter('verificationCode');
    }

    public function setVerificationCode($value)
    {
        return $this->setParameter('verificationCode', $value);
    }

    public function getRsaKey()
    {
        return $this->getParameter('rsaKey');
    }

    public function setRsaKey($value)
    {
        return $this->setParameter('rsaKey', $value);
    }

    public function getHashType()
    {
        return $this->getParameter('hashType');
    }

    public function setHashType($value)
    {
        return $this->setParameter('hashType', $value);
    }

    public function getCurrentDomain()
    {
        return $this->getParameter('currentDomain');
    }

    public function setCurrentDomain($value)
    {
        return $this->setParameter('currentDomain', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value - can be 'pl', 'en', 'fr', 'es', 'it', 'ru'
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        throw new BadMethodCallException('This method is not supported.');
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest(DeregisterRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

}
