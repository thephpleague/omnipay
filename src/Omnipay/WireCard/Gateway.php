<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\WireCard;

use Omnipay\Common\AbstractGateway;
use Omnipay\WireCard\Message\CompletePurchaseRequest;
use Omnipay\WireCard\Message\PurchaseRequest;

/**
 * @link https://integration.wirecard.at/doku.php/start
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'WireCard';
    }

    public function getDefaultParameters()
    {
        return array(
            'installationId' => '',
            'secretWord' => '',
            'callbackPassword' => '',
            'testMode' => false,
        );
    }

    public function getInstallationId()
    {
        return $this->getParameter('installationId');
    }

    public function setInstallationId($value)
    {
        return $this->setParameter('installationId', $value);
    }

    public function getSecretWord()
    {
        return $this->getParameter('secretWord');
    }

    public function setSecretWord($value)
    {
        return $this->setParameter('secretWord', $value);
    }

    public function getCallbackPassword()
    {
        return $this->getParameter('callbackPassword');
    }

    public function setCallbackPassword($value)
    {
        return $this->setParameter('callbackPassword', $value);
    }

    public function initializeDataStorage()
    {
        return "https://checkout.wirecard.com/seamless/dataStorage/init";
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\WireCard\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\WireCard\Message\CompletePurchaseRequest', $parameters);
    }
}

