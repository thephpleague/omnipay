<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $action;
    protected $vendor;
    protected $testMode;
    protected $simulatorMode;

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

    public function createResponse($data)
    {
        return new Response($data);
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
        $data['Vendor'] = $this->vendor;

        return $data;
    }
}
