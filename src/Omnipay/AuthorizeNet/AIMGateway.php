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
use Omnipay\AuthorizeNet\Message\AIMPurchaseRequest;
use Omnipay\AuthorizeNet\Message\CaptureRequest;
use Omnipay\Common\AbstractGateway;

/**
 * Authorize.Net AIM Class
 */
class AIMGateway extends AbstractGateway
{
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

    public function authorize(array $options = null)
    {
        $request = new AIMAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function capture(array $options = null)
    {
        $request = new CaptureRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new AIMPurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
