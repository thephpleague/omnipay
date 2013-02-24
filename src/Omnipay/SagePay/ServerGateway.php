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

use Omnipay\SagePay\Message\ServerAuthorizeRequest;
use Omnipay\SagePay\Message\ServerCompleteAuthorizeRequest;
use Omnipay\SagePay\Message\ServerPurchaseRequest;

/**
 * Sage Pay Server Gateway
 */
class ServerGateway extends DirectGateway
{
    public function getName()
    {
        return 'Sage Pay Server';
    }

    public function authorize($options = null)
    {
        $request = new ServerAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completeAuthorize($options = null)
    {
        $request = new ServerCompleteAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function purchase($options = null)
    {
        $request = new ServerPurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completePurchase($options = null)
    {
        return $this->completeAuthorize($options);
    }

    protected function getEndpoint($service)
    {
        $service = strtolower($service);
        if ($service == 'payment' || $service == 'deferred') {
            $service = 'vspserver-register';
        }

        return parent::getEndpoint($service);
    }
}
