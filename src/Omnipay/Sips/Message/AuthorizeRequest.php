<?php

namespace Omnipay\Sips\Message;

use Omnipay\Common\CreditCard;

/**
 * Class AuthorizeRequest
 * @package Omnipay\Sips\Message
 */
class AuthorizeRequest extends Request
{
    /**
     * Sends request to the Sips binary file for payment authorization
     *
     * @return AuthorizeResponse
     */
    public function send()
    {
        $params = $this->getSipsParamString();
        $path_bin = $this->getSipsRequestExecPath();
        $result = exec("$path_bin $params");

        return $this->response = new AuthorizeResponse($this, $result);
    }

    /**
     * Gets a string representing all the parameters to pass to Sips
     *
     * @return string
     */
    protected function getSipsParamString()
    {
        $params = 'merchant_id=' . $this->getMerchant()->getId();
        $params .= ' merchant_language=' . $this->getMerchant()->getLanguage();
        $params .= ' merchant_country=' . $this->getMerchant()->getCountry();

        $params .= " pathfile=" . $this->getSipsPathFilePath();
        $params .= " amount=" . $this->getAmountInteger();
        $params .= " currency_code=" . $this->getCurrencyNumeric();
        $params .= " transaction_id=" . $this->getTransactionId();

        /** @var CreditCard $card */
        $card = $this->getCard();

        $params .= " customer_email=" . $card->getEmail();
        $params .= " customer_ip_address=" . $this->getClientIp();

        $cartParams = $this->getSipsCartString();
        $params .= " caddie=" . $cartParams;

        $params .= " cancel_return_url=" . $this->getCancelUrl();
        $params .= " automatic_response_url=" . $this->getNotifyUrl();
        $params .= " normal_return_url=" . $this->getReturnUrl();

        return trim($params);
    }

    /**
     * Gets a string representing the shopping cart
     * and the owner of the payment card
     *
     * @return string
     */
    protected function getSipsCartString()
    {
        $cartParams = array();

        $cartParams[] = $this->getClientIp();

        /** @var CreditCard $card */
        $card = $this->getCard();

        $cartParams[] = $card->getBillingFirstName();
        $cartParams[] = $card->getBillingLastName();

        $cartParams[] = $card->getBillingCompany();
        $cartParams[] = $card->getBillingAddress1() . ' ' . $card->getBillingAddress2();

        $cartParams[] = $card->getBillingCity();
        $cartParams[] = $card->getBillingPostcode();
        $cartParams[] = $card->getBillingCountry();


        $cartParams[] = $card->getBillingPhone();
        $cartParams[] = $card->getEmail();

        $cartParams[] = $this->getTransactionId();

        $cartParams[] = $this->getAmountInteger();

        return trim(base64_encode(serialize($cartParams)));
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();
        return array('amount' => $this->getAmount());
    }
}
