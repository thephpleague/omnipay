<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet;

use Omnipay\AuthorizeNet\Message\SIMAuthorizeRequest;
use Omnipay\AuthorizeNet\Message\SIMCompleteAuthorizeRequest;

/**
 * Authorize.Net SIM Class
 */
class SIMGateway extends AIMGateway
{
    public function getName()
    {
        return 'Authorize.Net SIM';
    }

    public function authorize(array $options = null)
    {
        $request = new SIMAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completeAuthorize(array $options = null)
    {
        $request = new SIMCompleteAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase(array $options = null)
    {
        $request = new SIMAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completePurchase(array $options = null)
    {
        return $this->completeAuthorize($options);
    }
}
