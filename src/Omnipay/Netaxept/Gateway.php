<?php

namespace Omnipay\Netaxept;

use Omnipay\Common\AbstractGateway;
use Omnipay\Netaxept\Message\PurchaseRequest;
use Omnipay\Netaxept\Message\CompletePurchaseRequest;

/**
 * Netaxept Gateway
 *
 * @link http://www.betalingsterminal.no/Netthandel-forside/Teknisk-veiledning/Overview/
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Netaxept';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Netaxept\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Netaxept\Message\CompletePurchaseRequest', $parameters);
    }
}
