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
use Omnipay\Common\Message\RequestInterface;
use Omnipay\SagePay\Message\CaptureRequest;
use Omnipay\SagePay\Message\DirectAuthorizeRequest;
use Omnipay\SagePay\Message\DirectPurchaseRequest;
use Omnipay\SagePay\Message\RefundRequest;
use Omnipay\SagePay\Message\ServerCompleteAuthorizeRequest;

/**
 * Sage Pay Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://live.sagepay.com/gateway/service';
    protected $testEndpoint = 'https://test.sagepay.com/gateway/service';
    protected $simulatorEndpoint = 'https://test.sagepay.com/Simulator';
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
        $request = new DirectAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completeAuthorize($options = null)
    {
        $request = new DirectAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function capture($options = null)
    {
        $request = new CaptureRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function purchase($options = null)
    {
        $request = new DirectPurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
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
        $request = new RefundRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function send(RequestInterface $request)
    {
        if ($request instanceof ServerCompleteAuthorizeRequest) {
            return $this->createResponse($request, $request->getData());
        }

        $url = $this->getEndpoint($request->getService());
        $httpResponse = $this->httpClient->post($url, null, $request->getData())->send();

        return $this->createResponse($request, $httpResponse->getBody());
    }

    protected function getEndpoint($service)
    {
        $service = strtolower($service);
        if ($service == 'payment' || $service == 'deferred') {
            $service = 'vspdirect-register';
        }

        if ($this->simulatorMode) {
            // hooray for consistency
            if ($service == 'vspdirect-register') {
                return $this->simulatorEndpoint.'/VSPDirectGateway.asp';
            } elseif ($service == 'vspserver-register') {
                return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=VendorRegisterTx';
            } elseif ($service == 'direct3dcallback') {
                return $this->simulatorEndpoint.'/VSPDirectCallback.asp';
            }

            return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=Vendor'.ucfirst($service).'Tx';
        }

        if ($this->testMode) {
            return $this->testEndpoint."/$service.vsp";
        }

        return $this->liveEndpoint."/$service.vsp";
    }
}
