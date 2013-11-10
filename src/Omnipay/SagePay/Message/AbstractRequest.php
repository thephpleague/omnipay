<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://live.sagepay.com/gateway/service';
    protected $testEndpoint = 'https://test.sagepay.com/gateway/service';
    protected $simulatorEndpoint = 'https://test.sagepay.com/Simulator';

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getSimulatorMode()
    {
        return $this->getParameter('simulatorMode');
    }

    public function setSimulatorMode($value)
    {
        return $this->setParameter('simulatorMode', $value);
    }

    public function getService()
    {
        return $this->action;
    }

    protected function getBaseData()
    {
        $data = array();
        $data['VPSProtocol'] = '2.23';
        $data['TxType'] = $this->action;
        $data['Vendor'] = $this->getVendor();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    public function getEndpoint()
    {
        $service = strtolower($this->getService());

        if ($this->getSimulatorMode()) {
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

        if ($this->getTestMode()) {
            return $this->testEndpoint."/$service.vsp";
        }

        return $this->liveEndpoint."/$service.vsp";
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
