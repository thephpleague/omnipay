<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Pin;

use Omnipay\Common\AbstractGateway;
use Omnipay\Pin\Message\PurchaseRequest;

/**
 * Pin Gateway
 *
 * @link https://pin.net.au/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $secretKey;
    protected $testMode;

    public function getName()
    {
        return 'Pin';
    }

    public function defineSettings()
    {
        return array(
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function setSecretKey($value)
    {
        $this->secretKey = $value;

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

    public function purchase(array $options = null)
    {
        $request = new PurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
