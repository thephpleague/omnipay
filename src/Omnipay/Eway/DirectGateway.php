<?php

namespace Omnipay\Eway;

use Omnipay\Common\AbstractGateway;

/**
 * eWAY Direct Payments Gateway
 */
class DirectGateway extends AbstractGateway
{
    public function getName()
    {
        return 'eWAY Direct';
    }

    public function getDefaultParameters()
    {
        return array(
            'customerId' => '',
            'testMode' => false,
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\DirectAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\DirectCaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\DirectPurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\DirectRefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway\Message\DirectVoidRequest', $parameters);
    }
}
