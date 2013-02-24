<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Payflow;

use Omnipay\Common\AbstractGateway;
use Omnipay\Payflow\Message\AuthorizeRequest;
use Omnipay\Payflow\Message\CaptureRequest;
use Omnipay\Payflow\Message\PurchaseRequest;
use Omnipay\Payflow\Message\RefundRequest;

/**
 * Payflow Pro Class
 *
 * @link https://www.x.com/sites/default/files/payflowgateway_guide.pdf
 */
class ProGateway extends AbstractGateway
{
    protected $username;
    protected $password;
    protected $vendor;
    protected $partner;
    protected $testMode;

    public function getName()
    {
        return 'Payflow';
    }

    public function defineSettings()
    {
        return array(
            'username' => '',
            'password' => '',
            'vendor' => '',
            'partner' => '',
            'testMode' => false,
        );
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

        return $this;
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

    public function getPartner()
    {
        return $this->partner;
    }

    public function setPartner($value)
    {
        $this->partner = $value;

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

    public function authorize(array $options = null)
    {
        $request = new AuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function capture(array $options = null)
    {
        $request = new CaptureRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new PurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function refund(array $options = null)
    {
        $request = new RefundRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
