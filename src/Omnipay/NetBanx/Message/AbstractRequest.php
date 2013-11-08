<?php

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\CreditCard;

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
     * Send request
     *
     * @return \Omnipay\Common\Message\ResponseInterface|void
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

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

    /**
     * Translate card type to internal NetBanx format
     *
     * @param  string $brand
     * @return string
     */
    protected function translateCardType($brand)
    {
        switch ($brand) {
            case CreditCard::BRAND_VISA:
                $cardType = 'VI';
                break;
            case CreditCard::BRAND_AMEX:
                $cardType = 'AM';
                break;
            case CreditCard::BRAND_DISCOVER:
                $cardType = 'DI';
                break;
            case CreditCard::BRAND_MASTERCARD:
                $cardType = 'MC';
                break;
            case CreditCard::BRAND_MAESTRO:
                $cardType = 'MD';
                break;
            case CreditCard::BRAND_LASER:
                $cardType = 'LA';
                break;
            case CreditCard::BRAND_SOLO:
                $cardType = 'SO';
                break;
            case CreditCard::BRAND_JCB:
                $cardType = 'JC';
                break;
            case CreditCard::BRAND_DINERS_CLUB:
                $cardType = 'DC';
                break;
            default:
                $cardType = 'VI';
        }

        return $cardType;
    }
}
