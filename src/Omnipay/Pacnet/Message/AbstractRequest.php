<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndPoint = 'https://raven.pacnetservices.com/realtime/';
    protected $testEndPoint = 'https://demo.pacnetservices.com/realtime/';

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('sharedSecret');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('sharedSecret', $value);
    }

    public function getPaymentRoutingNumber()
    {
        return $this->getParameter('paymentRoutingNumber');
    }

    public function setPaymentRoutingNumber($value)
    {
        return $this->setParameter('paymentRoutingNumber', $value);
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

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();
        return new Response($this, $httpResponse->getBody());
    }

    public function getData()
    {
        $this->validate('username', 'sharedSecret', 'paymentRoutingNumber');

        $data = array();

        $data['RAPIVersion'] = 2;
        $data['UserName'] = $this->getUsername();
        $data['PRN'] = $this->getPaymentRoutingNumber();
        $data['Timestamp'] = gmdate('Y-m-d\TH:i:s.000\Z');
        $data['RequestID'] = $this->getRequestID();

        return $data;
    }
}
