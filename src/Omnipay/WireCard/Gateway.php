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
use Omnipay\WireCard\Message\PurchaseRequest;
use Omnipay\WireCard\Message\Refund;

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
        return [ 
            'secret'           => '',
            'username'         => '56501',
            'password'         => 'TestXAPTER',
            'transaction_mode' => 'demo',
            'testMode'         => true,
            'business_case_signature' => '56501',

           'JCB'       => '3528000000000000',
           'AIRPLUS'   => '122000000000000',
           'DINERS'    => '38000000000000',
           'AMEX'      => '370000000000000',
           'VISA'      => '4200000000000000',
           'MASTER'    => '5500000000000000',
           'DISCOVER'  => '6011000000000000',
           'Maestro'   => '675940000000000002',
           'test_card' => '4200000000000000',
        ];
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\WireCard\Message\PurchaseRequest', 
            $parameters
        );
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(
            '\Omnipay\WireCard\Message\RefundRequest', 
            $parameters
        );
    }

    public function tokenize(array $parameters = [])
    {
    }

    /**
     * This is a joke. If you have to 
     * ask you just aint cool
     */
    public function chargeBack(array $parameters)
    {
        return $this->cheekyBugger();
    }

}

