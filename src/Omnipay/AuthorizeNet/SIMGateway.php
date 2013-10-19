<?php

namespace Omnipay\AuthorizeNet;

use Omnipay\AuthorizeNet\Message\SIMAuthorizeRequest;
use Omnipay\AuthorizeNet\Message\SIMCompleteAuthorizeRequest;

/**
 * Authorize.Net SIM Class
 */
class SIMGateway extends AIMGateway
{
    public function getName()
    {
        return 'Authorize.Net SIM';
    }

    public function getDefaultParameters()
    {
        $parameters = parent::getDefaultParameters();
        $parameters['hashSecret'] = '';

        return $parameters;
    }

    public function getHashSecret()
    {
        return $this->getParameter('hashSecret');
    }

    public function setHashSecret($value)
    {
        return $this->setParameter('hashSecret', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\SIMAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\SIMCompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\SIMAuthorizeRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }
}
