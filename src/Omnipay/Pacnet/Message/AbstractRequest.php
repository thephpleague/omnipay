<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndPoint = 'https://raven.pacnetservices.com/realtime/';
    protected $testEndPoint = 'https://demo.pacnetservices.com/realtime/';

    public function getUserName()
    {
        return $this->getParameter('UserName');
    }

    public function setUserName($value)
    {
        return $this->setParameter('UserName', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('Password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('Password', $value);
    }

    public function getPRN()
    {
        return $this->getParameter('PRN');
    }

    public function setPRN($value)
    {
        return $this->setParameter('PRN', $value);
    }

    public function getTrackingNumber()
    {
        return $this->getParameter('TrackingNumber');
    }

    public function setTrackingNumber($value)
    {
        return $this->setParameter('TrackingNumber', $value);
    }

    public function getRequestID()
    {
        return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public function setRequestID($value)
    {
        return $this->setParameter('RequestID', $value);
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function send()
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $this->getData()
        );
        $httpResponse = $httpRequest->send();

        return $this->response = new Response($this, $httpResponse);
    }
}
