<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Netaxept;

use Omnipay\Common\AbstractGateway;
use Omnipay\Netaxept\Message\PurchaseRequest;
use Omnipay\Netaxept\Message\CompletePurchaseRequest;

/**
 * Netaxept Gateway
 *
 * @link http://www.betalingsterminal.no/Netthandel-forside/Teknisk-veiledning/Overview/
 */
class Gateway extends AbstractGateway
{
    protected $merchantId;
    protected $token;
    protected $testMode;

    public function getName()
    {
        return 'Netaxept';
    }

    public function defineSettings()
    {
        return array(
            'merchantId' => '',
            'token' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function setMerchantId($value)
    {
        $this->merchantId = $value;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($value)
    {
        $this->token = $value;

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

    public function completePurchase(array $options = null)
    {
        $request = new CompletePurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
