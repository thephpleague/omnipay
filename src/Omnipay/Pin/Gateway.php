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

use Guzzle\Common\Event;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Pin\Message\PurchaseRequest;

/**
 * Pin Gateway
 *
 * @link https://pin.net.au/docs/api
 */
class Gateway extends AbstractGateway
{
    protected $liveEndpoint = 'https://api.pin.net.au/1';
    protected $testEndpoint = 'https://test-api.pin.net.au/1';
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

    public function purchase($options = null)
    {
        $request = new PurchaseRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function send(RequestInterface $request)
    {
        // don't throw exceptions for 422 errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->getStatusCode() == 422) {
                    $event->stopPropagation();
                }
            }
        );

        $httpResponse = $this->httpClient->post($this->getEndpoint().'/charges', null, $request->getData())
            ->setHeader('Authorization', 'Basic '.base64_encode($this->secretKey.':'))
            ->send();

        return $this->createResponse($request, $httpResponse->json());
    }

    protected function getEndpoint()
    {
        return $this->testMode ? $this->testEndpoint : $this->liveEndpoint;
    }
}
