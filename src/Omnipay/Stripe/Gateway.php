<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Stripe;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Stripe\Message\PurchaseRequest;
use Omnipay\Stripe\Message\RefundRequest;

/**
 * Stripe Gateway
 *
 * @link https://stripe.com/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $endpoint = 'https://api.stripe.com/v1';
    protected $apiKey;

    public function getName()
    {
        return 'Stripe';
    }

    public function defineSettings()
    {
        return array(
            'apiKey' => '',
        );
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($value)
    {
        $this->apiKey = $value;

        return $this;
    }

    public function purchase($options = null)
    {
        $request = new PurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function refund($options = null)
    {
        $request = new RefundRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function send(RequestInterface $request)
    {
        // don't throw exceptions for 402 errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->getStatusCode() == 402) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($this->endpoint.$request->getUrl(), null, $request->getData())
            ->setHeader('Authorization', 'Basic '.base64_encode($this->apiKey.':'))
            ->send();

        return $this->createResponse($request, $httpResponse->json());
    }
}
