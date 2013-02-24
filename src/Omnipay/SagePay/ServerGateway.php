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

    public function authorize(array $options = null)
    {
        $request = new ServerAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completeAuthorize(array $options = null)
    {
        $request = new ServerCompleteAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new ServerPurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completePurchase(array $options = null)
    {
        return $this->completeAuthorize($options);
    }
}
