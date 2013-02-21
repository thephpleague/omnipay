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

use Omnipay\AuthorizeNet\Message\AIMAuthorizeRequest;
use Omnipay\AuthorizeNet\Message\CaptureRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

/**
 * Authorize.Net AIM Class
 */
class AIMGateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://secure.authorize.net/gateway/transact.dll';
    protected $developerEndpoint = 'https://test.authorize.net/gateway/transact.dll';
    protected $apiLoginId;
    protected $transactionKey;
    protected $testMode;
    protected $developerMode;

    public function getName()
    {
        return 'Authorize.Net AIM';
    }

    public function defineSettings()
    {
        return array(
            'apiLoginId' => '',
            'transactionKey' => '',
            'testMode' => false,
            'developerMode' => false,
        );
    }

    public function getApiLoginId()
    {
        return $this->apiLoginId;
    }

    public function setApiLoginId($value)
    {
        $this->apiLoginId = $value;

        return $this;
    }

    public function getTransactionKey()
    {
        return $this->transactionKey;
    }

    public function setTransactionKey($value)
    {
        $this->transactionKey = $value;

        return $this;
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;

        return $this;
    }

    public function getDeveloperMode()
    {
        return $this->developerMode;
    }

    public function setDeveloperMode($value)
    {
        $this->developerMode = $value;

        return $this;
    }

    public function authorize($options = null)
    {
        $request = new AIMAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setMethod('AUTH_ONLY');
    }

    public function capture($options = null)
    {
        $request = new CaptureRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function purchase($options = null)
    {
        $request = new AIMAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setMethod('AUTH_CAPTURE');
    }

    public function send(RequestInterface $request)
    {
        $httpResponse = $this->httpClient->get($this->getEndpoint(), null, $request->getData())->send();

        return $this->createResponse($request, $httpResponse->getBody());
    }

    public function getEndpoint()
    {
        return $this->developerMode ? $this->developerEndpoint : $this->liveEndpoint;
    }
}
