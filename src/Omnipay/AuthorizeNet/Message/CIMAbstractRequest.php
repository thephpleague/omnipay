<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet\Message;

/**
 * Authorize.Net CIM Abstract Request
 */
abstract class CIMAbstractRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.authorize.net/xml/v1/request.api';
    protected $developerEndpoint = 'https://apitest.authorize.net/xml/v1/request.api';

    public function getCustomerEmail()
    {
        return $this->getParameter('customerEmail');
    }

    public function setCustomerEmail($value)
    {
        $this->setParameter('customerEmail', $value);   
    }

    protected function getBaseData()
    {
        $data = new \SimpleXMLElement('<createCustomerProfileRequest />');
        $data->addAttribute('xmlns', 'AnetApi/xml/v1/schema/AnetApiSchema.xsd﻿');
        
        $data->merchantAuthentication->name = $this->getApiLoginId();
        $data->merchantAuthentication->transactionKey = $this->getTransactionKey();
        
        $data->profile->merchantCustomerId = $this->getCustomerId();
        $data->profile->description = $this->getDescription();
        $data->profile->email = $this->getCustomerEmail();

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData()->asXML())->send();

        return $this->response = new CIMResponse($this, $httpResponse->xml());
    }

    public function getEndpoint()
    {
        return $this->getDeveloperMode() ? $this->developerEndpoint : $this->liveEndpoint;
    }
}
