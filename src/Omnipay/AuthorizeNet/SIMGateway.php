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
use Omnipay\AuthorizeNet\Message\SIMAuthorizeResponse;
use Omnipay\AuthorizeNet\Message\SIMCompleteAuthorizeRequest;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;

/**
 * Authorize.Net SIM Class
 */
class SIMGateway extends AIMGateway
{
    public function getName()
    {
        return 'Authorize.Net SIM';
    }

    public function authorize($options = null)
    {
        $request = new SIMAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setMethod('AUTH_ONLY');
    }

    public function completeAuthorize($options = null)
    {
        $request = new SIMCompleteAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);

        $request = new Request($options);
        if (!$this->validateReturnHash($request, $this->httpRequest->request->get('x_MD5_Hash'))) {
            throw new InvalidResponseException();
        }

        return new SIMResponse($this->httpRequest->request->all());
    }

    public function purchase($options = null)
    {
        $request = new SIMAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setMethod('AUTH_CAPTURE');
    }

    public function completePurchase($options = null)
    {
        return $this->completeAuthorize($options);
    }

    public function send(RequestInterface $request)
    {
        $response = $this->createResponse($request, $request->getData());

        if ($response instanceof SIMAuthorizeResponse) {
            $response->setRedirectUrl($this->getEndpoint());
        }

        return $response;
    }
}
