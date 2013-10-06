<?php

namespace Omnipay\Dummy;

use Omnipay\Common\AbstractGateway;
use Omnipay\Dummy\Message\AuthorizeRequest;

/**
 * Dummy Gateway
 *
 * This gateway is useful for testing. It simply authorizes any payment made using a valid
 * credit card number and expiry.
 *
 * Any card number which passes the Luhn algorithm and ends in an even number is authorized,
 * for example: 4242424242424242
 *
 * Any card number which passes the Luhn algorithm and ends in an odd number is declined,
 * for example: 4111111111111111
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Dummy';
    }

    public function getDefaultParameters()
    {
        return array();
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Dummy\Message\AuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->authorize($parameters);
    }
}
