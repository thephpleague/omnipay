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

    public function getSharedSecret()
    {
        return $this->getParameter('SharedSecret');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('SharedSecret', $value);
    }

    public function getPRN()
    {
        return $this->getParameter('PRN');
    }

    public function setPRN($value)
    {
        return $this->setParameter('PRN', $value);
    }

    public function getRequestID()
    {
        return sprintf(
            '%04X%04X%04X%04X%04X%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

    public function setRequestID($value)
    {
        return $this->setParameter('RequestID', $value);
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();
        return $this->createResponse($httpResponse->getBody());
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
