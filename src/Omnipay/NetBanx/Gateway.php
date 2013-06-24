<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\NetBanx;

use Omnipay\Common\AbstractGateway;

/**
 * NetBanx Class
 */
class Gateway extends AbstractGateway
{
    const DECISION_ACCEPTED = 'ACCEPTED';
    const CREATE_CARD_AMOUNT = '1.00';
    const CODE_OK = '0';

    /**
     * Get name of the gateway
     *
     * @return string
     */
    public function getName()
    {
        return 'NetBanx Gateway';
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'accountNumber' => '',
            'storeId' => '',
            'storePassword' => '',
            'testMode' => false,
        );
    }

    /**
     * Authorize a new amount
     *
     * @param  array $parameters
     * @return mixed
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Capture authorized amount
     *
     * @param  array                      $parameters An array of options
     * @return \Omnipay\ResponseInterface
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\CaptureRequest', $parameters);
    }

    /**
     * Create a new charge (combined authorize + capture).
     *
     * @param array An array of options
     * @return \Omnipay\ResponseInterface
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\PurchaseRequest', $parameters);
    }

    /**
     * Void transaction
     *
     * @param  array                      $parameters An array of options
     * @return \Omnipay\ResponseInterface
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\VoidRequest', $parameters);
    }

    /**
     * Create card
     *
     * @param  array $parameters
     * @return mixed
     */
    public function createCard(array $parameters = array())
    {
        $parameters['amount'] = self::CREATE_CARD_AMOUNT;

        return $this->createRequest('\Omnipay\NetBanx\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Setter for Account Number
     *
     * @param string $value
     * @return $this
     */
    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * Getter for Account Number
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Setter for Store ID
     *
     * @param string $value
     * @return $this
     */
    public function setStoreId($value)
    {
        return $this->setParameter('storeId', $value);
    }

    /**
     * Getter for Store ID
     *
     * @return string
     */
    public function getStoreId()
    {
        return $this->getParameter('storeId');
    }

    /**
     * Setter for Store Password
     *
     * @param string $value
     * @return $this
     */
    public function setStorePassword($value)
    {
        return $this->setParameter('storePassword', $value);
    }

    /**
     * Getter for Store Password
     *
     * @return string
     */
    public function getStorePassword()
    {
        return $this->getParameter('storePassword');
    }
}
