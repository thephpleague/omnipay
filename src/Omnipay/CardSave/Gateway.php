<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\CardSave;

use Omnipay\CardSave\Message\CompletePurchaseRequest;
use Omnipay\CardSave\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * CardSave Gateway
 *
 * @link http://www.cardsave.net/dev-downloads
 */
class Gateway extends AbstractGateway
{
    protected $merchantId;
    protected $password;

    public function getName()
    {
        return 'CardSave';
    }

    public function defineSettings()
    {
        return array(
            'merchantId' => '',
            'password' => '',
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

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

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
