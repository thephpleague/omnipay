<?php

namespace Omnipay\Sips\Message;

use Omnipay\Common\CreditCard;

/**
 * Class RequestCall
 *
 * Defines a call to the Sips Request binary
 *
 * @package Omnipay\Sips\Message
 */
class RequestCall extends SipsBinaryCall
{
    /**
     * Sends request to the Sips binary file for payment authorization
     *
     * @return RequestResult
     */
    public function send()
    {
        $params = $this->buildRequest();
        $path_bin = $this->getSipsRequestExecPath();
        $result = exec("$path_bin $params");

        return $this->response = new RequestResult($this, $result);
    }

    /**
     * Gets a string representing all the parameters to pass to Sips
     *
     * @return string
     */
    protected function buildRequest()
    {
        /** @var CreditCard $card */
        $card = $this->getCard();

        $params = array(

            'pathfile' => $this->getSipsPathFilePath(),

            'merchant_id' => $this->getMerchant()->getId(),
            'merchant_language' => $this->getMerchant()->getLanguage(),
            'merchant_country' => $this->getMerchant()->getCountry(),

            'amount' => $this->getAmountInteger(),
            'currency_code' => $this->getCurrencyNumeric(),
            'transaction_id' => $this->getTransactionId(),
            'order_id' => $this->getTransactionId(),

            'customer_email' => $card->getEmail(),
            'customer_ip_address' => $this->getClientIp(),

            'caddie' => $this->buildCaddie(),

            'cancel_return_url' => $this->getCancelUrl(),
            'automatic_response_url' => $this->getNotifyUrl(),
            'normal_return_url' => $this->getReturnUrl()
        );

        $response = array();
        foreach ($params as $key => $value) {
            $response[] = $key . '=' . $value;
        }

        return implode(' ', $response);
    }

    /**
     * Gets a unique string representing
     * this specific shopping cart
     *
     * @return string
     */
    protected function buildCaddie()
    {

        /** @var CreditCard $card */
        $card = $this->getCard();

        $cartParams = array(
            $this->getClientIp(),
            $card->getBillingFirstName(),
            $card->getBillingLastName(),
            $card->getBillingCompany(),
            $card->getBillingAddress1() . ' ' . $card->getBillingAddress2(),
            $card->getBillingCity(),
            $card->getBillingPostcode(),
            $card->getBillingCountry(),
            $card->getBillingPhone(),
            $card->getEmail(),
            $this->getTransactionId(),
            $this->getAmountInteger()
        );

        return trim(base64_encode(serialize($cartParams)));
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();
        return array('amount' => $this->getAmount());
    }
}
