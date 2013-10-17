<?php

namespace Omnipay\Eway\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * eWAY Direct Abstract Request
 */
abstract class DirectAbstractRequest extends AbstractRequest
{

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new DirectResponse($this, $httpResponse->getBody());
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function setOption1($value)
    {
        return $this->setParameter('option1', $value);
    }

    public function getOption1()
    {
        return $this->getParameter('option1');
    }

    public function setOption2($value)
    {
        return $this->setParameter('option2', $value);
    }

    public function getOption2()
    {
        return $this->getParameter('option2');
    }

    public function setOption3($value)
    {
        return $this->setParameter('option3', $value);
    }

    public function getOption3()
    {
        return $this->getParameter('option3');
    }

    /**
     * Get End Point
     *
     * Depends on Test or Live environment
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
