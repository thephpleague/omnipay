<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet;

/**
 * Authorize.Net CIM Class
 */
class CIMGateway extends AIMGateway
{
    public function getName()
    {
        return 'Authorize.Net CIM';
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMCaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMPurchaseRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMVoidRequest', $parameters);
    }

    public function createProfile(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMCreateProfileRequest', $parameters);
    }

    public function updateProfile(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMUpdateProfileRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMCreateCardRequest', $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMUpdateCardRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\AuthorizeNet\Message\CIMDeleteCardRequest', $parameters);
    }
}
