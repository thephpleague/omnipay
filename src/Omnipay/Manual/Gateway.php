<?php

namespace Omnipay\Manual;

use Omnipay\Common\AbstractGateway;

/**
 * Manual Gateway
 *
 * This gateway is useful for processing check or direct debit payments. It simply
 * authorizes every payment.
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Manual';
    }

    public function getDefaultParameters()
    {
        return array();
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Manual\Message\Request', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Manual\Message\Request', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Manual\Message\Request', $parameters);
    }
}
