<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\SagePay\Message\CaptureRequest;
use Omnipay\SagePay\Message\DirectAuthorizeRequest;
use Omnipay\SagePay\Message\DirectPurchaseRequest;
use Omnipay\SagePay\Message\RefundRequest;

/**
 * Sage Pay Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    protected $vendor;
    protected $testMode;
    protected $simulatorMode;

    public function getName()
    {
        return 'Sage Pay Direct';
    }

    public function defineSettings()
    {
        return array(
            'vendor' => '',
            'testMode' => false,
            'simulatorMode' => false,
        );
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($value)
    {
        $this->vendor = $value;

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

    public function getSimulatorMode()
    {
        return $this->simulatorMode;
    }

    public function setSimulatorMode($value)
    {
        $this->simulatorMode = $value;

        return $this;
    }

    public function authorize($options = null)
    {
        $request = new DirectAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completeAuthorize($options = null)
    {
        $request = new DirectAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function capture($options = null)
    {
        $request = new CaptureRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase($options = null)
    {
        $request = new DirectPurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    /**
     * Only used for returning from Direct 3D Authentication
     */
    public function completePurchase($options = null)
    {
        return $this->completeAuthorize($options);
    }

    public function refund($options = null)
    {
        $request = new RefundRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
