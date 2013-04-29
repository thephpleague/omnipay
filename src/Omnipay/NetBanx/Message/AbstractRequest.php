<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\NetBanx\Message;

/**
 * NetBanx Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live EndPoint
     *
     * @var string
     */
    protected $liveEndpoint = 'https://webservices.optimalpayments.com/creditcardWS/CreditCardServlet/v1';

    /**
     * Developer EndPoint
     *
     * @var string
     */
    protected $developerEndpoint = 'https://webservices.test.optimalpayments.com/creditcardWS/CreditCardServlet/v1';

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

    /**
     * Getter for customer ID
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    /**
     * Setter for customr ID
     *
     * @param string $value
     * @return $this
     */
    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    /**
     * Setter for Card Type
     *
     * @param string $value
     * @return $this
     */
    public function setCardType($value)
    {
        return $this->setParameter('cardType', $value);
    }

    /**
     * Getter for Card Type
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->getParameter('cardType');
    }

    /**
     * Send request
     *
     * @return \Omnipay\Common\Message\ResponseInterface|void
     */
    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->response = new Response($this, $httpResponse->getBody());
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
        return $this->getTestMode() ? $this->developerEndpoint : $this->liveEndpoint;
    }

    /**
     * Get base data
     *
     * @return array
     */
    protected function getBaseData()
    {
        $data = array();
        $data['txnMode'] = $this->txnMode;

        return $data;
    }
}
